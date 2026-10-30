<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendapatanController extends Controller
{
    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function index(Request $request): View
    {
        $years = DB::table('tahun_anggarans')->orderByDesc('nama_tahun_anggaran')->get(['id', 'nama_tahun_anggaran', 'status']);
        $yearId = collect([$request->input('tahun_anggaran_id'), session('tahun_anggaran_id')])
            ->first(fn ($id) => $id && $years->contains('id', $id));
        $yearId ??= $years->firstWhere('status', 'aktif')?->id ?? $years->first()?->id;
        $year = $years->firstWhere('id', $yearId);
        if ($year) {
            $request->session()->put(['tahun_anggaran_id' => $year->id, 'tahun_anggaran' => $year->nama_tahun_anggaran]);
        }

        $isAdmin = $this->isAdmin();
        $units = $isAdmin ? DB::table('units')->orderBy('nama_unit')->get(['id', 'nama_unit']) : collect();
        $unitId = $isAdmin
            ? ($units->contains('id', $request->input('unit_id')) ? $request->input('unit_id') : null)
            : auth()->user()->unit_id;

        $targets = $yearId ? DB::table('target_pendapatans as target')
            ->join('units', 'units.id', '=', 'target.unit_id')
            ->where('target.tahun_anggaran_id', $yearId)
            ->when($unitId, fn ($query) => $query->where('target.unit_id', $unitId))
            ->orderBy('units.nama_unit')->orderBy('target.kode_rekening')
            ->get(['target.*', 'units.nama_unit']) : collect();

        $realizations = $targets->isEmpty() ? collect() : DB::table('realisasi_pendapatans')
            ->whereIn('target_pendapatan_id', $targets->pluck('id'))
            ->get()->groupBy('target_pendapatan_id');
        $targets = $targets->map(function ($target) use ($realizations) {
            $target->monthly = array_fill(1, 12, 0.0);
            foreach ($realizations->get($target->id, collect()) as $realization) {
                $target->monthly[(int) $realization->bulan] = (float) $realization->jumlah_realisasi;
            }
            $target->total_realisasi = array_sum($target->monthly);
            $target->sisa_target = (float) $target->target_tahunan - $target->total_realisasi;
            return $target;
        });

        $expenseMonthly = $this->expenseMonthlyTotals($year, $unitId);
        $summary = [
            'realisasi_belanja' => array_sum($expenseMonthly),
            'target_pendapatan' => (float) $targets->sum('target_tahunan'),
            'realisasi_pendapatan' => (float) $targets->sum('total_realisasi'),
        ];
        $targetOptions = $targets;

        return view('pendapatan.index', compact(
            'years', 'year', 'yearId', 'units', 'unitId', 'isAdmin', 'targets', 'targetOptions',
            'expenseMonthly', 'summary'
        ))->with('months', self::MONTHS);
    }

    public function saveTarget(Request $request): RedirectResponse
    {
        $isAdmin = $this->isAdmin();
        $unitId = $isAdmin ? $request->input('unit_id') : auth()->user()->unit_id;
        $data = $request->validate([
            'tahun_anggaran_id' => ['required', 'uuid', Rule::exists('tahun_anggarans', 'id')],
            'unit_id' => [$isAdmin ? 'required' : 'nullable', 'uuid', Rule::exists('units', 'id')],
            'kode_rekening' => ['required', 'string', 'max:100'],
            'nama_rekening' => ['required', 'string', 'max:255'],
            'target_tahunan' => ['required', 'numeric', 'min:0'],
        ]);
        abort_unless($unitId, 422, 'Pilih unit untuk menyimpan target pendapatan.');

        $target = DB::table('target_pendapatans')
            ->where('tahun_anggaran_id', $data['tahun_anggaran_id'])
            ->where('unit_id', $unitId)
            ->where('kode_rekening', trim($data['kode_rekening']))->first();
        $values = [
            'nama_rekening' => trim($data['nama_rekening']),
            'target_tahunan' => $data['target_tahunan'],
            'created_by' => auth()->id(),
            'updated_at' => now(),
        ];
        if ($target) {
            DB::table('target_pendapatans')->where('id', $target->id)->update($values);
        } else {
            DB::table('target_pendapatans')->insert($values + [
                'id' => (string) Str::uuid(),
                'tahun_anggaran_id' => $data['tahun_anggaran_id'],
                'unit_id' => $unitId,
                'kode_rekening' => trim($data['kode_rekening']),
                'created_at' => now(),
            ]);
        }

        return $this->backToReport($request, 'Target rekening pendapatan berhasil disimpan.');
    }

    public function saveRealization(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'target_pendapatan_id' => ['required', 'uuid', Rule::exists('target_pendapatans', 'id')],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'jumlah_realisasi' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);
        $target = DB::table('target_pendapatans')->where('id', $data['target_pendapatan_id'])->first();
        abort_unless($target && ($this->isAdmin() || $target->unit_id === auth()->user()->unit_id), 404);

        $realization = DB::table('realisasi_pendapatans')
            ->where('target_pendapatan_id', $target->id)->where('bulan', $data['bulan'])->first();
        $values = [
            'jumlah_realisasi' => $data['jumlah_realisasi'],
            'keterangan' => $data['keterangan'] ?? null,
            'created_by' => auth()->id(),
            'updated_at' => now(),
        ];
        if ($realization) {
            DB::table('realisasi_pendapatans')->where('id', $realization->id)->update($values);
        } else {
            DB::table('realisasi_pendapatans')->insert($values + [
                'id' => (string) Str::uuid(),
                'target_pendapatan_id' => $target->id,
                'bulan' => $data['bulan'],
                'created_at' => now(),
            ]);
        }

        return $this->backToReport($request, 'Realisasi pendapatan bulanan berhasil disimpan.');
    }

    private function backToReport(Request $request, string $message): RedirectResponse
    {
        return redirect()->route('pendapatan.index', array_filter([
            'tahun_anggaran_id' => $request->input('tahun_anggaran_id'),
            'unit_id' => $request->input('unit_id'),
        ]))->with('success', $message);
    }

    private function expenseMonthlyTotals(?object $year, ?string $unitId): array
    {
        $totals = array_fill(1, 12, 0.0);
        if (! $year) return $totals;
        preg_match('/\d{4}/', (string) $year->nama_tahun_anggaran, $yearMatch);
        $calendarYear = (int) ($yearMatch[0] ?? now()->year);
        $from = $calendarYear . '-01-01';
        $to = $calendarYear . '-12-31';
        $scopeUsulan = fn ($query) => $query->where('usulan.tahun_anggaran_id', $year->id)
            ->where('usulan.status_usulan', 'approved')
            ->when($unitId, fn ($q) => $q->where('usulan.unit_id', $unitId));

        if (\Illuminate\Support\Facades\Schema::hasTable('master_spj_payment_transactions')) {
            $events = DB::table('master_spj_payment_transactions as payment')
                ->join('master_spjs as master', 'master.id', '=', 'payment.master_spj_id')
                ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
                ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
                ->where($scopeUsulan)
                ->whereBetween('payment.tanggal_pembayaran', [$from, $to])
                ->get(['payment.amount', 'payment.tanggal_pembayaran', 'payment.bulan_pembayaran']);
            foreach ($events as $event) {
                $month = $this->monthNumber($event->bulan_pembayaran, $event->tanggal_pembayaran);
                $totals[$month] += (float) $event->amount;
            }
        }

        $legacy = DB::table('master_spjs as master')
            ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->where($scopeUsulan)
            ->whereNotNull('master.tanggal_pembayaran')
            ->whereNotNull('master.pembayaran')->where('master.pembayaran', '>', 0)
            ->whereBetween('master.tanggal_pembayaran', [$from, $to])
            ->when(\Illuminate\Support\Facades\Schema::hasTable('master_spj_payment_transactions'), fn ($query) => $query->whereNotExists(function ($subquery) {
                $subquery->selectRaw('1')->from('master_spj_payment_transactions as payment')
                    ->whereColumn('payment.master_spj_id', 'master.id');
            }))
            ->get(['master.pembayaran as amount', 'master.tanggal_pembayaran', 'master.bulan_pembayaran']);
        foreach ($legacy as $event) {
            $month = $this->monthNumber($event->bulan_pembayaran, $event->tanggal_pembayaran);
            $totals[$month] += (float) $event->amount;
        }

        return $totals;
    }

    private function monthNumber(?string $monthName, ?string $date): int
    {
        $monthName = mb_strtolower(trim((string) $monthName));
        foreach (self::MONTHS as $month => $label) {
            if (mb_strtolower($label) === $monthName) return $month;
        }
        return $date ? (int) \Illuminate\Support\Carbon::parse($date)->format('n') : 1;
    }

    private function isAdmin(): bool
    {
        return auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');
    }
}
