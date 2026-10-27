<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringAnggaranController extends Controller
{
    public function blud(Request $request, PaguAnggaranService $paguService)
    {
        return $this->show($request, $paguService, 'BLUD');
    }

    public function keseluruhan(Request $request, PaguAnggaranService $paguService)
    {
        return $this->show($request, $paguService, null);
    }

    private function show(Request $request, PaguAnggaranService $paguService, ?string $source)
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

        $accounts = DB::table('rekening_belanjas')
            ->orderBy('kode_rekening_belanja')
            ->get(['id_kode_rekening_belanja', 'kode_rekening_belanja', 'nama_rekening_belanja']);
        $codeById = $accounts->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');
        $paguRows = $yearId ? DB::table('pagu_anggarans as pagu')
            ->join('rekening_belanjas as rekening', 'rekening.id_kode_rekening_belanja', '=', 'pagu.id_kode_rekening_belanja')
            ->where('pagu.tahun_anggaran_id', $yearId)
            ->when($source, fn ($query) => $query->where('pagu.jenis_sumber_dana', $source))
            ->get(['pagu.id_kode_rekening_belanja', 'pagu.jenis_sumber_dana', 'pagu.jumlah_pagu', 'rekening.kode_rekening_belanja'])
            : collect();

        $dataByCode = [];
        foreach ($paguRows as $pagu) {
            $code = trim((string) $pagu->kode_rekening_belanja, '.');
            if ($code === '') continue;
            $dataByCode[$code] ??= ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
            $dataByCode[$code]['anggaran'] += (float) $pagu->jumlah_pagu;
        }

        $itemRows = $yearId ? DB::table('usulan_belanja_items as item')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item.usulan_belanja_id')
            ->join('rkbus', 'rkbus.id', '=', 'item.rkbu_id')
            ->leftJoin('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'rkbus.sumber_dana_id')
            ->where('usulan.tahun_anggaran_id', $yearId)
            ->where('usulan.status_usulan', 'approved')
            ->when($source === 'BLUD', fn ($query) => $query->whereRaw("LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) LIKE '%blud%' AND LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) NOT LIKE '%non blud%'"))
            ->when($source === 'Non BLUD', fn ($query) => $query->whereRaw("LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) NOT LIKE '%blud%'"))
            ->get([
                'item.id as item_id', 'item.usulan_belanja_id', 'item.rkbu_id', 'item.harga_satuan', 'item.volume', 'item.ppn',
            ])
            : collect();

        $rkbuAccountIds = Rkbu::query()->whereIn('id', $itemRows->pluck('rkbu_id')->unique())
            ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan'])
            ->get()->mapWithKeys(fn (Rkbu $rkbu) => [$rkbu->id => $paguService->resolveAccountId($rkbu)]);

        $spjByUsulan = $yearId ? DB::table('spjs as spj')
            ->leftJoin('master_spjs as master', 'master.spj_id', '=', 'spj.id')
            ->whereIn('spj.usulan_belanja_id', DB::table('usulan_belanjas')->where('tahun_anggaran_id', $yearId)->where('status_usulan', 'approved')->select('id'))
            ->orderBy('spj.created_at')
            ->get([
                'spj.usulan_belanja_id', 'spj.pengadaan_step', 'master.bruto', 'master.pembayaran',
                'master.status_serah_terima_bendahara',
            ])->keyBy('usulan_belanja_id')
            : collect();

        foreach ($itemRows->groupBy('usulan_belanja_id') as $usulanId => $items) {
            $usulanTotal = $items->sum(fn ($item) => (float) $item->harga_satuan * (float) $item->volume * (1 + (float) $item->ppn / 100));
            if ($usulanTotal <= 0) continue;
            $spj = $spjByUsulan->get($usulanId);
            $paid = (float) ($spj->pembayaran ?? 0);
            $acceptedByFinance = ($spj->status_serah_terima_bendahara ?? null) === 'Selesai';
            $isCompleted = ($spj->pengadaan_step ?? null) === 'selesai';
            $financeTotal = $acceptedByFinance ? max(0, (float) ($spj->bruto ?? $usulanTotal) - $paid) : 0.0;

            foreach ($items as $item) {
                $accountId = $rkbuAccountIds->get($item->rkbu_id);
                $rkbuCode = trim((string) $codeById->get($accountId), '.');
                if ($rkbuCode === '') continue;
                $itemAmount = (float) $item->harga_satuan * (float) $item->volume * (1 + (float) $item->ppn / 100);
                $share = $itemAmount / $usulanTotal;
                $dataByCode[$rkbuCode] ??= ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                $dataByCode[$rkbuCode]['realisasi'] += $paid * $share;
                if ($acceptedByFinance) {
                    $dataByCode[$rkbuCode]['keuangan'] += $financeTotal * $share;
                } elseif (! $isCompleted) {
                    $dataByCode[$rkbuCode]['pengadaan'] += $itemAmount;
                }
            }
        }

        $ledgerRows = $accounts->map(function ($account) use ($dataByCode) {
            $code = trim((string) $account->kode_rekening_belanja, '.');
            $totals = ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
            foreach ($dataByCode as $detailCode => $values) {
                if ($detailCode !== $code && ! str_starts_with($detailCode, $code . '.')) continue;
                foreach ($totals as $column => $_) $totals[$column] += $values[$column];
            }
            if (array_sum($totals) <= 0) return null;
            $totals['sisa'] = $totals['anggaran'] - $totals['pengadaan'] - $totals['keuangan'] - $totals['realisasi'];
            $totals['persentase'] = $totals['anggaran'] > 0 ? round($totals['realisasi'] / $totals['anggaran'] * 100, 2) : 0;
            $totals['kode'] = $account->kode_rekening_belanja;
            $totals['nama'] = $account->nama_rekening_belanja;
            $totals['level'] = max(0, substr_count($code, '.') - 1);
            return $totals;
        })->filter()->values();

        $summary = [
            'anggaran' => (float) $paguRows->sum('jumlah_pagu'),
            'pengadaan' => (float) collect($dataByCode)->sum('pengadaan'),
            'keuangan' => (float) collect($dataByCode)->sum('keuangan'),
            'realisasi' => (float) collect($dataByCode)->sum('realisasi'),
        ];
        $summary['sisa'] = $summary['anggaran'] - $summary['pengadaan'] - $summary['keuangan'] - $summary['realisasi'];
        $summary['persentase'] = $summary['anggaran'] > 0 ? round($summary['realisasi'] / $summary['anggaran'] * 100, 2) : 0;

        $programAccountMap = DB::table('rekening_belanjas as rekening')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening.id_aktivitas')
            ->join('programs as program', 'program.id_program', '=', 'aktivitas.id_program')
            ->join('sub_kegiatans as sub_kegiatan', 'sub_kegiatan.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans as kegiatan', 'kegiatan.id_kegiatan', '=', 'sub_kegiatan.id_kegiatan')
            ->get([
                'rekening.kode_rekening_belanja', 'rekening.nama_rekening_belanja',
                'program.id_program', 'program.kode_program', 'program.nama_program',
                'aktivitas.id_aktivitas', 'aktivitas.kode_aktivitas', 'aktivitas.nama_aktivitas',
                'sub_kegiatan.id_sub_kegiatan', 'sub_kegiatan.kode_sub_kegiatan', 'sub_kegiatan.nama_sub_kegiatan',
                'kegiatan.id_kegiatan', 'kegiatan.kode_kegiatan', 'kegiatan.nama_kegiatan',
            ])->keyBy('kode_rekening_belanja');

        $programRows = [];
        foreach ($dataByCode as $code => $values) {
            $account = $programAccountMap->get($code);
            if (! $account) continue;
            $path = [
                ['key' => 'program:' . $account->id_program, 'level' => 0, 'label' => $account->kode_program . ' ' . $account->nama_program],
                ['key' => 'kegiatan:' . $account->id_kegiatan, 'level' => 1, 'label' => $account->kode_kegiatan . ' ' . $account->nama_kegiatan],
                ['key' => 'sub:' . $account->id_sub_kegiatan, 'level' => 2, 'label' => $account->kode_sub_kegiatan . ' ' . $account->nama_sub_kegiatan],
                ['key' => 'rekening:' . $code, 'level' => 3, 'label' => $code . ' ' . $account->nama_rekening_belanja],
            ];
            foreach ($path as $node) {
                $programRows[$node['key']] ??= $node + ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                foreach (['anggaran', 'pengadaan', 'keuangan', 'realisasi'] as $column) {
                    $programRows[$node['key']][$column] += (float) $values[$column];
                }
            }
        }
        $programRows = collect($programRows)->map(function ($row) {
            $row['persentase'] = $row['anggaran'] > 0 ? round($row['realisasi'] / $row['anggaran'] * 100, 2) : 0;
            return $row;
        })->values();

        $typeBudgets = ['pegawai' => 0.0, 'barjas' => 0.0, 'modal' => 0.0];
        foreach ($paguRows as $pagu) {
            $code = trim((string) $pagu->kode_rekening_belanja, '.');
            $type = str_starts_with($code, '5.1.01') ? 'pegawai' : (str_starts_with($code, '5.2') ? 'modal' : 'barjas');
            $typeBudgets[$type] += (float) $pagu->jumlah_pagu;
        }

        return view('dashboard.monitoring_anggaran', [
            'years' => $years,
            'year' => $year,
            'yearId' => $yearId,
            'source' => $source,
            'ledgerRows' => $ledgerRows,
            'programRows' => $programRows,
            'summary' => $summary,
            'typeBudgets' => $typeBudgets,
        ]);
    }
}
