<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\Asset\Penempatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenempatanController extends Controller
{
    public function index(): View
    {
        $penempatans = Penempatan::query()->orderBy('lokasi_barang')->orderBy('gedung')->orderBy('tempat_lokasi')->get();
        $assetCounts = DB::table('assets')->select('id_penempatan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('id_penempatan')->groupBy('id_penempatan')->pluck('total', 'id_penempatan');

        return view('master_backend.setting_input.penempatan.index', [
            'penempatans' => $penempatans,
            'assetCounts' => $assetCounts,
            'title' => 'Data Penempatan',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        Penempatan::create($data);

        return redirect()->route('penempatans.index')->with('success', 'Data penempatan berhasil ditambahkan.');
    }

    public function update(Request $request, Penempatan $penempatan): RedirectResponse
    {
        $penempatan->update($request->validate($this->rules()));

        return redirect()->route('penempatans.index')->with('success', 'Data penempatan berhasil diperbarui.');
    }

    public function destroy(Penempatan $penempatan): RedirectResponse
    {
        if (DB::table('assets')->where('id_penempatan', $penempatan->id_penempatan)->exists()) {
            return redirect()->route('penempatans.index')->with('error', 'Penempatan ini masih digunakan oleh aset dan tidak dapat dihapus.');
        }

        $penempatan->delete();

        return redirect()->route('penempatans.index')->with('success', 'Data penempatan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'lokasi_barang' => ['required', 'string', 'max:500'],
            'gedung' => ['nullable', 'string', 'max:255'],
            'tempat_lokasi' => ['required', 'string', 'max:500'],
            'penanggung_jawab' => ['nullable', 'string', 'max:255'],
        ];
    }
}
