<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingInput\TanggalPerencana;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TanggalPerencanaController extends Controller
{
    public function index()
    {
        $tanggal_perencanaans = TanggalPerencana::with(['tahunAnggaran.fase', 'fase'])
            ->orderByDesc('tanggal')
            ->orderBy('created_at')
            ->get();
        $tahun_anggarans = TahunAnggaran::with('fase')->orderByDesc('nama_tahun_anggaran')->get();
        $fases = Fase::orderBy('urutan')->orderBy('nama_fase')->get();

        return view('master_backend.setting_input.tanggal_perencanaan.index', compact(
            'tanggal_perencanaans', 'tahun_anggarans', 'fases'
        ), [
            'title' => 'Tanggal Pengesahan',
            'title2' => 'Tanggal Pengesahan RKBU / RBA',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        TanggalPerencana::create($data);

        return redirect()->route('tanggal_perencanaans.index')->with('success', 'Tanggal pengesahan berhasil ditambahkan.');
    }

    public function update(Request $request, TanggalPerencana $tanggal_perencanaan)
    {
        $tanggal_perencanaan->update($this->validated($request));

        return redirect()->route('tanggal_perencanaans.index')->with('success', 'Tanggal pengesahan berhasil diperbarui.');
    }

    public function destroy(TanggalPerencana $tanggal_perencanaan)
    {
        $tanggal_perencanaan->delete();

        return redirect()->route('tanggal_perencanaans.index')->with('success', 'Tanggal pengesahan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'id_tahun_anggaran' => ['required', 'uuid', Rule::exists('tahun_anggarans', 'id')],
            'fase_id' => ['required', 'uuid', Rule::exists('fases', 'id')],
            'tanggal' => ['required', 'date'],
            'no_dpa' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['aktif', 'tidak aktif'])],
        ]);

        $tahunAnggaran = TahunAnggaran::findOrFail($data['id_tahun_anggaran']);
        if ($tahunAnggaran->fase_id !== $data['fase_id']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'fase_id' => 'Fase harus sesuai dengan fase pada tahun anggaran yang dipilih.',
            ]);
        }

        return $data;
    }
}
