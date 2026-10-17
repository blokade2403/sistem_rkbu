<?php

namespace App\Http\Controllers\WorkFlow;

use App\Http\Controllers\Controller;
use App\Models\WorkFlow\PptkKategori;
use Illuminate\Http\Request;

class PptkKategoriController extends Controller
{
    public function index()
    {
        $pptkKategoris = PptkKategori::all();

        return view(
            'master_backend.workflow_setting.pptk_kategori.index',
            compact('pptkKategoris'),
            ['title' => 'PPTK Kategori - Workflow Setting'],
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        PptkKategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, PptkKategori $pptkKategori)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        $pptkKategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(PptkKategori $pptkKategori)
    {
        $pptkKategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
