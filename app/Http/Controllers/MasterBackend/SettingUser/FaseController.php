<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Support\Str;

class FaseController extends Controller
{
    public function index()
    {
        $fases = Fase::all();
        return view('master_backend.setting_users.fase.index', [
            'fases'     => $fases,
            'title'     => 'Halaman Fase',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.fase.create', [
            'title'     => 'Halaman Fase',
            'title2'    => 'Fase'
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_fase' => 'required'],
            ['nama_fase' => 'Nama fase belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }

        $fase = new Fase([
            'nama_fase'  => $request->nama_fase
        ]);

        $fase->save();
        return redirect()->route('fases.index')->with('success', 'Data Struktur fase Berhasil di Tambahkan');
    }

    public function show(Fase $fase)
    {
        //
    }

    public function edit(Fase $fase)
    {
        return view('master_backend.setting_users.fase.edit', [
            'fase'              => $fase,
            'title'             => 'Halaman Fase',
        ]);
    }


    public function update(Request $request, Fase $fase)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_fase' => 'required'],
            ['nama_fase' => 'Nama fase belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }
        // dd($fase);

        $fase->update($request->all());
        return redirect()->route('fases.index')->with('success', 'Data Struktur fase Berhasil di Update');
    }

    public function destroy($id)
    {

        $fase = Fase::findOrFail($id);
        $fase->delete();

        return redirect()->route('fases.index')->with('success', 'Data fase Berhasil dihapus.');
    }
}
