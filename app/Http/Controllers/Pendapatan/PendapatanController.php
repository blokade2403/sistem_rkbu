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
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function index(Request $request): View
    {
        $years = DB::table('tahun_anggarans')->orderByDesc('nama_tahun_anggaran')->get(['id', 'nama_tahun_anggaran', 'status']);
        $yearId = collect([$request->input('tahun_anggaran_id'), session('tahun_anggaran_id')])
            ->first(fn ($id) => $id && $years->contains('id', $id));
        $yearId ??= $years->firstWhere('status', 'aktif')?->id ?? $years->first()?->id;
        $year = $years->firstWhere('id', $yearId);
        if ($year) $request->session()->put(['tahun_anggaran_id' => $year->id, 'tahun_anggaran' => $year->nama_tahun_anggaran]);

        $accounts = DB::table('rekening_pendapatans')->where('aktif', true)->orderBy('kode_rekening')->get();
        $targets = $yearId ? DB::table('target_pendapatans as target')
            ->join('rekening_pendapatans as rekening', 'rekening.id', '=', 'target.rekening_pendapatan_id')
            ->where('target.tahun_anggaran_id', $yearId)->orderBy('rekening.kode_rekening')
            ->get(['target.*', 'rekening.kode_rekening', 'rekening.nama_rekening', 'rekening.is_silpa']) : collect();
        $targetHistories = $targets->isEmpty() ? collect() : DB::table('target_pendapatan_histories')
            ->whereIn('target_pendapatan_id', $targets->pluck('id'))->orderByDesc('created_at')->get()->groupBy('target_pendapatan_id');
        $targets->each(fn ($target) => $target->histories = $targetHistories->get($target->id, collect()));
        $realizations = $targets->isEmpty() ? collect() : DB::table('realisasi_pendapatans')
            ->whereIn('target_pendapatan_id', $targets->pluck('id'))->get()->groupBy('target_pendapatan_id');
        $targets = $targets->map(function ($target) use ($realizations) {
            $target->monthly = array_fill(1, 12, 0.0);
            $target->realizationRows = [];
            foreach ($realizations->get($target->id, collect()) as $realization) {
                $target->monthly[(int) $realization->bulan] = (float) $realization->jumlah_realisasi;
                $target->realizationRows[(int) $realization->bulan] = $realization;
            }
            $target->total_realisasi = array_sum($target->monthly);
            $target->sisa_target = (float) $target->target_tahunan - $target->total_realisasi;
            return $target;
        });

        $expenseMonthly = $this->expenseMonthlyTotals($year);
        $summary = [
            'realisasi_belanja' => array_sum($expenseMonthly),
            'target_pendapatan' => (float) $targets->sum('target_tahunan'),
            'realisasi_pendapatan' => (float) $targets->sum('total_realisasi'),
        ];
        $summary['selisih_kas'] = $summary['realisasi_pendapatan'] - $summary['realisasi_belanja'];

        return view('pendapatan.index', compact('years', 'year', 'yearId', 'accounts', 'targets', 'expenseMonthly', 'summary'))
            ->with('months', self::MONTHS);
    }

    public function saveAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_rekening' => ['required', 'string', 'max:100', 'regex:/^(4(?:\.|$)|SILPA$)/i', Rule::unique('rekening_pendapatans', 'kode_rekening')],
            'nama_rekening' => ['required', 'string', 'max:255'],
            'is_silpa' => ['nullable', 'boolean'],
        ], ['kode_rekening.regex' => 'Kode harus termasuk Akun 4 atau menggunakan kode SILPA.']);
        DB::table('rekening_pendapatans')->insert([
            'id' => (string) Str::uuid(), 'kode_rekening' => strtoupper(trim($data['kode_rekening'])),
            'nama_rekening' => trim($data['nama_rekening']), 'is_silpa' => strtoupper(trim($data['kode_rekening'])) === 'SILPA' || $request->boolean('is_silpa'),
            'aktif' => true, 'created_by' => auth()->id(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return $this->backToReport($request, 'Rekening pendapatan berhasil ditambahkan.');
    }

    public function updateAccount(Request $request, string $id): RedirectResponse
    {
        $account = DB::table('rekening_pendapatans')->where('id', $id)->first();
        abort_unless($account, 404);
        $data = $request->validate([
            'kode_rekening' => ['required', 'string', 'max:100', 'regex:/^(4(?:\\.|$)|SILPA$)/i', Rule::unique('rekening_pendapatans', 'kode_rekening')->ignore($id)],
            'nama_rekening' => ['required', 'string', 'max:255'],
            'is_silpa' => ['nullable', 'boolean'],
        ], ['kode_rekening.regex' => 'Kode harus termasuk Akun 4 atau menggunakan kode SILPA.']);
        DB::table('rekening_pendapatans')->where('id', $id)->update([
            'kode_rekening' => strtoupper(trim($data['kode_rekening'])),
            'nama_rekening' => trim($data['nama_rekening']),
            'is_silpa' => strtoupper(trim($data['kode_rekening'])) === 'SILPA' || $request->boolean('is_silpa'),
            'updated_at' => now(),
        ]);
        return $this->backToReport($request, 'Rekening pendapatan berhasil diperbarui.');
    }

    public function saveTarget(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tahun_anggaran_id' => ['required', 'uuid', Rule::exists('tahun_anggarans', 'id')],
            'rekening_pendapatan_id' => ['required', 'uuid', Rule::exists('rekening_pendapatans', 'id')],
            'target_tahunan' => ['required', 'numeric', 'min:0'],
            'lampiran_surat' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        $target = DB::table('target_pendapatans')->where('tahun_anggaran_id', $data['tahun_anggaran_id'])
            ->where('rekening_pendapatan_id', $data['rekening_pendapatan_id'])->first();
        $year = DB::table('tahun_anggarans as tahun')->leftJoin('fases as fase', 'fase.id', '=', 'tahun.fase_id')
            ->where('tahun.id', $data['tahun_anggaran_id'])->first(['fase.urutan as fase_urutan']);
        $attachment = $request->hasFile('lampiran_surat')
            ? $request->file('lampiran_surat')->store('pendapatan/bukti-target', 'public')
            : ($target->lampiran_surat_path ?? null);
        $before = $target ? (float) $target->target_tahunan : null;
        $targetId = $target?->id ?? (string) Str::uuid();
        $values = ['target_tahunan' => $data['target_tahunan'], 'lampiran_surat_path' => $attachment, 'created_by' => auth()->id(), 'updated_at' => now()];
        if ($target) DB::table('target_pendapatans')->where('id', $target->id)->update($values);
        else DB::table('target_pendapatans')->insert($values + ['id' => $targetId, 'tahun_anggaran_id' => $data['tahun_anggaran_id'], 'rekening_pendapatan_id' => $data['rekening_pendapatan_id'], 'created_at' => now()]);
        if ((int) ($year->fase_urutan ?? 0) === 2) {
            DB::table('target_pendapatan_histories')->insert([
                'id' => (string) Str::uuid(), 'target_pendapatan_id' => $targetId, 'user_id' => auth()->id(),
                'target_sebelum' => $before, 'target_sesudah' => $data['target_tahunan'],
                'lampiran_surat_path' => $attachment, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        return $this->backToReport($request, 'Target tahunan berhasil disimpan.');
    }

    public function updateTarget(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'target_tahunan' => ['required', 'numeric', 'min:0'],
            'lampiran_surat' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        $target = DB::table('target_pendapatans')->where('id', $id)->first();
        abort_unless($target, 404);
        $year = DB::table('tahun_anggarans as tahun')->leftJoin('fases as fase', 'fase.id', '=', 'tahun.fase_id')
            ->where('tahun.id', $target->tahun_anggaran_id)->first(['fase.urutan as fase_urutan']);
        $attachment = $request->hasFile('lampiran_surat')
            ? $request->file('lampiran_surat')->store('pendapatan/bukti-target', 'public')
            : $target->lampiran_surat_path;
        DB::table('target_pendapatans')->where('id', $id)->update([
            'target_tahunan' => $data['target_tahunan'], 'lampiran_surat_path' => $attachment,
            'created_by' => auth()->id(), 'updated_at' => now(),
        ]);
        if ((int) ($year->fase_urutan ?? 0) === 2) {
            DB::table('target_pendapatan_histories')->insert([
                'id' => (string) Str::uuid(), 'target_pendapatan_id' => $id, 'user_id' => auth()->id(),
                'target_sebelum' => $target->target_tahunan, 'target_sesudah' => $data['target_tahunan'],
                'lampiran_surat_path' => $attachment, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        return $this->backToReport($request, 'Target rekening berhasil diperbarui.');
    }

    public function saveRealization(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'target_pendapatan_id' => ['required', 'uuid', Rule::exists('target_pendapatans', 'id')],
            'bulan' => ['required', 'integer', 'between:1,12'], 'jumlah_realisasi' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);
        $target = DB::table('target_pendapatans')->where('id', $data['target_pendapatan_id'])->first();
        abort_unless($target, 404);
        $realization = DB::table('realisasi_pendapatans')->where('target_pendapatan_id', $target->id)->where('bulan', $data['bulan'])->first();
        $values = ['jumlah_realisasi' => $data['jumlah_realisasi'], 'keterangan' => $data['keterangan'] ?? null, 'created_by' => auth()->id(), 'updated_at' => now()];
        if ($realization) DB::table('realisasi_pendapatans')->where('id', $realization->id)->update($values);
        else DB::table('realisasi_pendapatans')->insert($values + ['id' => (string) Str::uuid(), 'target_pendapatan_id' => $target->id, 'bulan' => $data['bulan'], 'created_at' => now()]);
        return $this->backToReport($request, 'Realisasi pendapatan bulanan berhasil disimpan.');
    }

    public function updateRealization(Request $request, string $id): RedirectResponse
    {
        $realization = DB::table('realisasi_pendapatans')->where('id', $id)->first();
        abort_unless($realization, 404);
        $data = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12', Rule::unique('realisasi_pendapatans', 'bulan')
                ->where('target_pendapatan_id', $realization->target_pendapatan_id)->ignore($id)],
            'jumlah_realisasi' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);
        DB::table('realisasi_pendapatans')->where('id', $id)->update([
            'bulan' => $data['bulan'], 'jumlah_realisasi' => $data['jumlah_realisasi'],
            'keterangan' => $data['keterangan'] ?? null, 'created_by' => auth()->id(), 'updated_at' => now(),
        ]);
        return $this->backToReport($request, 'Realisasi pendapatan berhasil diperbarui.');
    }

    private function backToReport(Request $request, string $message): RedirectResponse
    {
        return redirect()->route('pendapatan.index', array_filter(['tahun_anggaran_id' => $request->input('tahun_anggaran_id')]))->with('success', $message);
    }

    private function expenseMonthlyTotals(?object $year): array
    {
        $totals = array_fill(1, 12, 0.0);
        if (! $year) return $totals;
        preg_match('/\d{4}/', (string) $year->nama_tahun_anggaran, $yearMatch);
        $calendarYear = (int) ($yearMatch[0] ?? now()->year);
        $from = $calendarYear . '-01-01'; $to = $calendarYear . '-12-31';
        $scope = fn ($query) => $query->where('usulan.tahun_anggaran_id', $year->id)->where('usulan.status_usulan', 'approved')
            ->whereRaw('UPPER(TRIM(sumber.nama_sumber_dana)) = ?', ['BLUD']);

        if (\Illuminate\Support\Facades\Schema::hasTable('master_spj_payment_transactions')) {
            $events = DB::table('master_spj_payment_transactions as payment')->join('master_spjs as master', 'master.id', '=', 'payment.master_spj_id')
                ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
                ->join('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'usulan.sumber_dana_id')
                ->where($scope)->whereBetween('payment.tanggal_pembayaran', [$from, $to])->get(['payment.amount', 'payment.tanggal_pembayaran', 'payment.bulan_pembayaran']);
            foreach ($events as $event) $totals[$this->monthNumber($event->bulan_pembayaran, $event->tanggal_pembayaran)] += (float) $event->amount;
        }
        $legacy = DB::table('master_spjs as master')->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->join('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'usulan.sumber_dana_id')->where($scope)
            ->whereNotNull('master.tanggal_pembayaran')->whereNotNull('master.pembayaran')->where('master.pembayaran', '>', 0)
            ->whereBetween('master.tanggal_pembayaran', [$from, $to])
            ->when(\Illuminate\Support\Facades\Schema::hasTable('master_spj_payment_transactions'), fn ($q) => $q->whereNotExists(fn ($sub) => $sub->selectRaw('1')->from('master_spj_payment_transactions as payment')->whereColumn('payment.master_spj_id', 'master.id')))
            ->get(['master.pembayaran as amount', 'master.tanggal_pembayaran', 'master.bulan_pembayaran']);
        foreach ($legacy as $event) $totals[$this->monthNumber($event->bulan_pembayaran, $event->tanggal_pembayaran)] += (float) $event->amount;
        return $totals;
    }

    private function monthNumber(?string $monthName, ?string $date): int
    {
        $monthName = mb_strtolower(trim((string) $monthName));
        foreach (self::MONTHS as $month => $label) if (mb_strtolower($label) === $monthName) return $month;
        return $date ? (int) \Illuminate\Support\Carbon::parse($date)->format('n') : 1;
    }
}
