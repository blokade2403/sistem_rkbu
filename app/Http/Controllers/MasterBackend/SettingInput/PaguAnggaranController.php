<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\PaguAnggaran;
use App\Models\MasterBackend\SettingInput\PaguAnggaranHistory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaguAnggaranController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->hasRole('Administrator'), 403);

            return $next($request);
        });
    }

    public function index(Request $request, PaguAnggaranService $paguService)
    {
        $tahunAnggarans = TahunAnggaran::query()->orderByDesc('nama_tahun_anggaran')->get();
        $requestedYearId = $request->input('tahun_anggaran_id');
        $sessionYearId = $request->session()->get('tahun_anggaran_id');
        $tahunAnggaranId = collect([$requestedYearId, $sessionYearId])
            ->first(fn ($id) => $id && $tahunAnggarans->contains('id', $id));
        $tahunAnggaranId ??= $tahunAnggarans->firstWhere('status', 'aktif')?->id ?? $tahunAnggarans->first()?->id;
        $tahunAnggaran = $tahunAnggarans->firstWhere('id', $tahunAnggaranId);
        if ($tahunAnggaran) {
            $request->session()->put(['tahun_anggaran_id' => $tahunAnggaran->id, 'tahun_anggaran' => $tahunAnggaran->nama_tahun_anggaran]);
        }

        $pagus = PaguAnggaran::query()
            ->with(['tahunAnggaran', 'rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas'])
            ->when($tahunAnggaranId, fn ($query) => $query->where('tahun_anggaran_id', $tahunAnggaranId))
            ->orderByDesc('tahun_anggaran_id')
            ->orderBy('id_kode_rekening_belanja')
            ->get()
            ->map(function (PaguAnggaran $pagu) use ($paguService) {
                $pagu->rkbu_terpakai = $paguService->totalRkbuForAccount(
                    $pagu->tahun_anggaran_id,
                    $pagu->id_kode_rekening_belanja
                );
                $pagu->rkbu_tervalidasi = $paguService->approvedRkbuForAccount(
                    $pagu->tahun_anggaran_id,
                    $pagu->id_kode_rekening_belanja
                );
                $pagu->usulan_terpakai = $paguService->totalUsulanForAccount(
                    $pagu->tahun_anggaran_id,
                    $pagu->id_kode_rekening_belanja
                );

                return $pagu;
            });

        $summary = [
            'pagu' => (float) $pagus->sum('jumlah_pagu'),
            'rkbu' => (float) $pagus->sum('rkbu_terpakai'),
            'usulan' => (float) $pagus->sum('usulan_terpakai'),
            'sisa_rkbu' => (float) $pagus->sum(fn ($pagu) => (float) $pagu->jumlah_pagu - (float) $pagu->rkbu_terpakai),
            'sisa_usulan' => (float) $pagus->sum(fn ($pagu) => (float) $pagu->jumlah_pagu - (float) $pagu->usulan_terpakai),
        ];

        return view('master_backend.setting_input.pagu_anggaran.index', compact('pagus', 'tahunAnggarans', 'tahunAnggaran', 'tahunAnggaranId', 'summary'), [
            'title' => 'Penguncian Pagu Anggaran',
            'title2' => 'Pagu Anggaran',
        ]);
    }

    public function create()
    {
        $rekenings = $this->rekeningOptions();
        $tahunAnggarans = TahunAnggaran::query()->orderByDesc('nama_tahun_anggaran')->get();
        $sessionYearId = session('tahun_anggaran_id');
        $defaultYearId = $sessionYearId && $tahunAnggarans->contains('id', $sessionYearId)
            ? $sessionYearId
            : ($tahunAnggarans->firstWhere('status', 'aktif')?->id ?? $tahunAnggarans->first()?->id);
        return view('master_backend.setting_input.pagu_anggaran.form', [
            'pagu' => new PaguAnggaran(['tahun_anggaran_id' => $defaultYearId]),
            'tahunAnggarans' => $tahunAnggarans,
            'rekenings' => $rekenings,
            'rekeningBySource' => $rekenings->groupBy(fn ($rekening) => $rekening->aktivitas?->sub_kegiatan?->sumber_danas?->nama_sumber_dana ?? 'Sumber dana belum diatur'),
            'action' => route('pagu_anggaran.store'),
            'method' => 'POST',
            'title' => 'Tambah Pagu Anggaran',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        PaguAnggaran::create($validated);
        $this->rememberYear($request, $validated['tahun_anggaran_id']);

        return redirect()->route('pagu_anggaran.index')->with('success', 'Pagu anggaran berhasil disimpan.');
    }

    public function edit(PaguAnggaran $paguAnggaran)
    {
        $rekenings = $this->rekeningOptions();
        return view('master_backend.setting_input.pagu_anggaran.form', [
            'pagu' => $paguAnggaran,
            'tahunAnggarans' => TahunAnggaran::query()->orderByDesc('nama_tahun_anggaran')->get(),
            'rekenings' => $rekenings,
            'rekeningBySource' => $rekenings->groupBy(fn ($rekening) => $rekening->aktivitas?->sub_kegiatan?->sumber_danas?->nama_sumber_dana ?? 'Sumber dana belum diatur'),
            'action' => route('pagu_anggaran.update', $paguAnggaran),
            'method' => 'PUT',
            'title' => 'Ubah Pagu Anggaran',
        ]);
    }

    public function update(Request $request, PaguAnggaran $paguAnggaran)
    {
        $validated = $this->validated($request, $paguAnggaran);
        $paguAnggaran->update($validated);
        $this->rememberYear($request, $validated['tahun_anggaran_id']);

        return redirect()->route('pagu_anggaran.index')->with('success', 'Pagu anggaran berhasil diperbarui.');
    }

    public function history(PaguAnggaran $paguAnggaran)
    {
        $history = PaguAnggaranHistory::query()
            ->with(['user', 'rkbu'])
            ->where('pagu_anggaran_id', $paguAnggaran->id)
            ->latest()
            ->paginate(25);

        return view('master_backend.setting_input.pagu_anggaran.history', compact('paguAnggaran', 'history'));
    }

    private function validated(Request $request, ?PaguAnggaran $pagu = null): array
    {
        return $request->validate([
            'tahun_anggaran_id' => ['required', 'uuid', 'exists:tahun_anggarans,id'],
            'id_kode_rekening_belanja' => [
                'required', 'uuid', 'exists:rekening_belanjas,id_kode_rekening_belanja',
                Rule::unique('pagu_anggarans', 'id_kode_rekening_belanja')
                    ->where('tahun_anggaran_id', $request->input('tahun_anggaran_id'))
                    ->ignore($pagu?->id),
            ],
            'jenis_sumber_dana' => ['required', Rule::in(['BLUD', 'Non BLUD'])],
            'jumlah_pagu' => ['required', 'numeric', 'min:0', 'max:9999999999999999.99'],
        ]);
    }

    private function rekeningOptions()
    {
        return RekeningBelanja::query()
            ->with('aktivitas.sub_kegiatan.sumber_danas')
            ->orderBy('kode_rekening_belanja')
            ->get();
    }

    private function rememberYear(Request $request, string $yearId): void
    {
        $yearName = TahunAnggaran::query()->whereKey($yearId)->value('nama_tahun_anggaran');
        if ($yearName !== null) {
            $request->session()->put(['tahun_anggaran_id' => $yearId, 'tahun_anggaran' => $yearName]);
        }
    }
}
