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

    public function index(PaguAnggaranService $paguService)
    {
        $pagus = PaguAnggaran::query()
            ->with(['tahunAnggaran', 'rekeningBelanja.aktivitas.sub_kegiatan.sumber_danas'])
            ->orderByDesc('tahun_anggaran_id')
            ->get()
            ->map(function (PaguAnggaran $pagu) use ($paguService) {
                $pagu->rkbu_terpakai = $paguService->totalRkbuForAccount(
                    $pagu->tahun_anggaran_id,
                    $pagu->id_kode_rekening_belanja
                );
                $pagu->usulan_terpakai = $paguService->totalUsulanForAccount(
                    $pagu->tahun_anggaran_id,
                    $pagu->id_kode_rekening_belanja
                );

                return $pagu;
            });

        return view('master_backend.setting_input.pagu_anggaran.index', compact('pagus'), [
            'title' => 'Penguncian Pagu Anggaran',
            'title2' => 'Pagu Anggaran',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_input.pagu_anggaran.form', [
            'pagu' => new PaguAnggaran(),
            'tahunAnggarans' => TahunAnggaran::query()->orderByDesc('nama_tahun_anggaran')->get(),
            'rekenings' => $this->rekeningOptions(),
            'action' => route('pagu_anggaran.store'),
            'method' => 'POST',
            'title' => 'Tambah Pagu Anggaran',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        PaguAnggaran::create($validated);

        return redirect()->route('pagu_anggaran.index')->with('success', 'Pagu anggaran berhasil disimpan.');
    }

    public function edit(PaguAnggaran $paguAnggaran)
    {
        return view('master_backend.setting_input.pagu_anggaran.form', [
            'pagu' => $paguAnggaran,
            'tahunAnggarans' => TahunAnggaran::query()->orderByDesc('nama_tahun_anggaran')->get(),
            'rekenings' => $this->rekeningOptions(),
            'action' => route('pagu_anggaran.update', $paguAnggaran),
            'method' => 'PUT',
            'title' => 'Ubah Pagu Anggaran',
        ]);
    }

    public function update(Request $request, PaguAnggaran $paguAnggaran)
    {
        $paguAnggaran->update($this->validated($request, $paguAnggaran));

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
}
