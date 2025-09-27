<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\Fungsional;

class FungsionalController extends Controller
{
    public function index()
    {
        $fungsionals = Fungsional::all();
        return view('master_backend.setting_users.fungsional.index', [
            'fungsionals'   => $fungsionals,
            'title'         => 'Fungsional Jabatan'
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.fungsional.create', ['title' => 'Fungsional Jabatan']);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['jabatan_fungsional' => 'required'],
            ['jabatan_fungsional' => 'Nama Fungsional belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }

        $fungsional = new Fungsional([
            'jabatan_fungsional'  => $request->jabatan_fungsional
        ]);

        $fungsional->save();
        return redirect()->route('fungsionals.index')->with('success', 'Data Struktur Jabatan Berhasil di Tambahkan');
    }

    public function show(Fungsional $jabatan)
    {
        //
    }

    public function edit(Fungsional $fungsional)
    {
        return view('master_backend.setting_users.fungsional.edit', [
            'fungsional'    => $fungsional,
            'title'         => 'Fungsional Jabatan'
        ]);
    }


    public function update(Request $request, Fungsional $fungsional)
    {
        $validator = Validator::make(
            $request->all(),
            ['jabatan_fungsional' => 'required'],
            ['jabatan_fungsional' => 'Nama Jabatan belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }
        // dd($jabatan);

        $fungsional->update($request->all());
        return redirect()->route('fungsionals.index')->with('success', 'Data Struktur Jabatan Berhasil di Update');
    }

    public function destroy($id)
    {

        $fungsional = Fungsional::findOrFail($id);
        $fungsional->delete();

        return redirect()->route('fungsionals.index')->with('success', 'Data Fungsional Jabatan Berhasil dihapus.');
    }
}
