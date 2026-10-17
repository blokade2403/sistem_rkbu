<?php

namespace App\Http\Controllers\MasterBackend\UserProfil;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\UserPptk;
use App\Models\WorkFlow\PptkKategori;
use Illuminate\Http\Request;

class UserPptkController extends Controller
{
    public function index()
    {
        $data = UserPptk::with(['user', 'pptkKategori'])->get();
        $users = User::orderBy('nama')->get();
        $pptks = PptkKategori::orderBy('nama_kategori')->get();

        return view(
            'master_backend.user_profil.user_pptk.index',
            compact('data', 'users', 'pptks'),
            [
                'title' => 'User PPTK - User Profil',
            ],
        );
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        $pptks = PptkKategori::orderBy('nama_kategori')->get();

        return view('master_backend.user_profil.user_pptk.create', compact('users', 'pptks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'pptk_kategori_id' => 'required',
        ]);

        UserPptk::create([
            'user_id' => $request->user_id,
            'pptk_kategori_id' => $request->pptk_kategori_id,
            'is_active' => true,
        ]);

        return redirect()->route('user-pptk.index')->with('success', 'Data PPTK berhasil disimpan');
    }

    public function edit($id)
    {
        $data = UserPptk::findOrFail($id);

        $users = User::orderBy('nama')->get();
        $pptks = PptkKategori::orderBy('nama_kategori')->get();

        return view('user_pptk.edit', compact('data', 'users', 'pptks'));
    }

    public function update(Request $request, $id)
    {
        $data = UserPptk::findOrFail($id);

        $data->update([
            'user_id' => $request->user_id,
            'pptk_kategori_id' => $request->pptk_kategori_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('user-pptk.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        UserPptk::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
