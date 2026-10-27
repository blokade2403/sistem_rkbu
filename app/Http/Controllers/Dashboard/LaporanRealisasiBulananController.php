<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanRealisasiBulananController extends Controller
{
    public function __invoke(Request $request, PaguAnggaranService $paguService)
    {
        $years = DB::table('tahun_anggarans')->orderByDesc('nama_tahun_anggaran')->get(['id', 'nama_tahun_anggaran', 'status']);
        $requestedYearId = $request->input('tahun_anggaran_id');
        $sessionYearId = $request->session()->get('tahun_anggaran_id');
        $yearId = collect([$requestedYearId, $sessionYearId])->first(fn ($id) => $id && $years->contains('id', $id));
        $yearId ??= $years->firstWhere('status', 'aktif')?->id ?? $years->first()?->id;
        $year = $years->firstWhere('id', $yearId);
        if ($year) {
            $request->session()->put(['tahun_anggaran_id' => $year->id, 'tahun_anggaran' => $year->nama_tahun_anggaran]);
        }

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthTotals = array_fill(1, 12, 0.0);
        $reportRows = [];
        $totalDpa = 0.0;

        if ($yearId) {
            $paguRows = DB::table('pagu_anggarans as pagu')
                ->join('rekening_belanjas as rekening', 'rekening.id_kode_rekening_belanja', '=', 'pagu.id_kode_rekening_belanja')
                ->where('pagu.tahun_anggaran_id', $yearId)
                ->orderBy('rekening.kode_rekening_belanja')
                ->get(['pagu.id_kode_rekening_belanja', 'pagu.jumlah_pagu', 'rekening.kode_rekening_belanja', 'rekening.nama_rekening_belanja']);

            $itemRows = DB::table('usulan_belanja_items as item')
                ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item.usulan_belanja_id')
                ->where('usulan.tahun_anggaran_id', $yearId)
                ->where('usulan.status_usulan', 'approved')
                ->get(['item.usulan_belanja_id', 'item.rkbu_id', 'item.harga_satuan', 'item.volume', 'item.ppn']);

            $rkbuAccounts = Rkbu::query()->whereIn('id', $itemRows->pluck('rkbu_id')->unique())
                ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan'])
                ->get()->mapWithKeys(fn (Rkbu $rkbu) => [$rkbu->id => $paguService->resolveAccountId($rkbu)]);
            $accountCodes = DB::table('rekening_belanjas')->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');

            $itemsByUsulan = $itemRows->map(function ($item) use ($rkbuAccounts, $accountCodes) {
                $accountId = $rkbuAccounts->get($item->rkbu_id);
                $amount = (float) $item->harga_satuan * (float) $item->volume * (1 + (float) $item->ppn / 100);
                return [
                    'usulan_id' => $item->usulan_belanja_id,
                    'account_id' => $accountId,
                    'code' => trim((string) $accountCodes->get($accountId), '.'),
                    'amount' => $amount,
                ];
            })->filter(fn ($item) => $item['code'] !== '')->groupBy('usulan_id');

            $accountPathMap = DB::table('rekening_belanjas as rekening')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening.id_aktivitas')
                ->join('programs as program', 'program.id_program', '=', 'aktivitas.id_program')
                ->join('sub_kegiatans as sub_kegiatan', 'sub_kegiatan.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('kegiatans as kegiatan', 'kegiatan.id_kegiatan', '=', 'sub_kegiatan.id_kegiatan')
                ->get([
                    'rekening.id_kode_rekening_belanja', 'rekening.kode_rekening_belanja', 'rekening.nama_rekening_belanja',
                    'program.id_program', 'program.kode_program', 'program.nama_program',
                    'aktivitas.id_aktivitas', 'aktivitas.kode_aktivitas', 'aktivitas.nama_aktivitas',
                    'sub_kegiatan.id_sub_kegiatan', 'sub_kegiatan.kode_sub_kegiatan', 'sub_kegiatan.nama_sub_kegiatan',
                    'kegiatan.id_kegiatan', 'kegiatan.kode_kegiatan', 'kegiatan.nama_kegiatan',
                ])->keyBy('id_kode_rekening_belanja');

            $getPath = function (?string $accountId, string $code) use ($accountPathMap): array {
                $account = $accountId ? $accountPathMap->get($accountId) : null;
                if (! $account) return [['key' => 'rekening:' . $code, 'level' => 0, 'label' => $code, 'sort' => $code]];
                $programSort = (string) $account->kode_program;
                $activitySort = $programSort . '|' . $account->kode_kegiatan;
                $subcategorySort = $activitySort . '|' . $account->kode_sub_kegiatan;
                return [
                    ['key' => 'program:' . $account->id_program, 'level' => 0, 'label' => $account->kode_program . ' ' . $account->nama_program, 'sort' => $programSort],
                    ['key' => 'kegiatan:' . $account->id_kegiatan, 'level' => 1, 'label' => $account->kode_kegiatan . ' ' . $account->nama_kegiatan, 'sort' => $activitySort],
                    ['key' => 'sub:' . $account->id_sub_kegiatan, 'level' => 2, 'label' => $account->kode_sub_kegiatan . ' ' . $account->nama_sub_kegiatan, 'sort' => $subcategorySort],
                    ['key' => 'rekening:' . $account->id_kode_rekening_belanja, 'level' => 3, 'label' => $code . ' ' . $account->nama_rekening_belanja, 'sort' => $subcategorySort . '|' . $code],
                ];
            };
            $addAmount = function (?string $accountId, string $code, string $column, float $amount, ?int $month = null) use (&$reportRows, $getPath): void {
                foreach ($getPath($accountId, $code) as $node) {
                    $reportRows[$node['key']] ??= $node + ['dpa' => 0.0, 'serapan' => array_fill(1, 12, 0.0)];
                    if ($month === null) $reportRows[$node['key']][$column] += $amount;
                    else $reportRows[$node['key']]['serapan'][$month] += $amount;
                }
            };

            foreach ($paguRows as $pagu) {
                $code = trim((string) $pagu->kode_rekening_belanja, '.');
                if ($code === '') continue;
                $value = (float) $pagu->jumlah_pagu;
                $totalDpa += $value;
                $addAmount((string) $pagu->id_kode_rekening_belanja, $code, 'dpa', $value);
            }

            $yearLabel = (string) ($year->nama_tahun_anggaran ?? now()->year);
            preg_match('/\d{4}/', $yearLabel, $yearMatch);
            $calendarYear = (int) ($yearMatch[0] ?? now()->year);
            $from = $calendarYear . '-01-01';
            $to = $calendarYear . '-12-31';

            $paymentEvents = collect();
            if (Schema::hasTable('master_spj_payment_transactions')) {
                $paymentEvents = DB::table('master_spj_payment_transactions as payment')
                    ->join('master_spjs as master', 'master.id', '=', 'payment.master_spj_id')
                    ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
                    ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
                    ->where('usulan.tahun_anggaran_id', $yearId)
                    ->whereBetween('payment.tanggal_pembayaran', [$from, $to])
                    ->get(['spj.usulan_belanja_id', 'payment.amount', 'payment.tanggal_pembayaran', 'payment.bulan_pembayaran']);
            }

            $legacyPayments = DB::table('master_spjs as master')
                ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
                ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
                ->where('usulan.tahun_anggaran_id', $yearId)
                ->whereNotNull('master.tanggal_pembayaran')
                ->whereNotNull('master.pembayaran')
                ->where('master.pembayaran', '>', 0)
                ->whereBetween('master.tanggal_pembayaran', [$from, $to])
                ->when(Schema::hasTable('master_spj_payment_transactions'), fn ($query) => $query->whereNotExists(function ($subquery) {
                    $subquery->selectRaw('1')->from('master_spj_payment_transactions as payment')
                        ->whereColumn('payment.master_spj_id', 'master.id');
                }))
                ->get(['spj.usulan_belanja_id', 'master.pembayaran as amount', 'master.tanggal_pembayaran', 'master.bulan_pembayaran']);
            $paymentEvents = $paymentEvents->merge($legacyPayments);
            $monthLookup = array_flip(array_map('mb_strtolower', $months));

            foreach ($paymentEvents as $payment) {
                $usulanItems = $itemsByUsulan->get($payment->usulan_belanja_id, collect());
                $baseTotal = (float) $usulanItems->sum('amount');
                if ($baseTotal <= 0) continue;
                $monthIndex = $monthLookup[mb_strtolower(trim((string) ($payment->bulan_pembayaran ?? '')))] ?? null;
                $month = $monthIndex === null ? (int) \Illuminate\Support\Carbon::parse($payment->tanggal_pembayaran)->format('n') : $monthIndex + 1;
                $amount = (float) $payment->amount;
                $monthTotals[$month] += $amount;
                foreach ($usulanItems as $item) {
                    $addAmount($item['account_id'] ? (string) $item['account_id'] : null, $item['code'], 'serapan', $amount * ($item['amount'] / $baseTotal), $month);
                }
            }
        }

        $reportRows = collect($reportRows)->sortBy('sort')->values();
        return view('dashboard.laporan_realisasi_bulanan', compact('years', 'year', 'yearId', 'months', 'monthTotals', 'reportRows', 'totalDpa'));
    }
}
