<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\Jabatan;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('master_backend.setting_users.jabatan.index', [
            'jabatans'      => $jabatans,
            'title'         => 'Halaman Jabatan',
            'title2'        => 'Jabatan',
            'routePrefix'   => 'jabatans'
        ]);
    }

    public function create()
    {
        $jabatans   = Jabatan::all();
        return view('master_backend.setting_users.jabatan.create', ['title' => 'Halaman Jabatan',  'title2'    => 'Jabatan', 'jabatans' => $jabatans, 'routePrefix' => 'jabatans']);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_jabatan' => 'required'],
            ['nama_jabatan' => 'Nama Jabatan belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }

        $jabatan = new Jabatan([
            'nama_jabatan'  => $request->nama_jabatan
        ]);

        $jabatan->save();
        return redirect()->route('jabatans.index')->with('success', 'Data Struktur Jabatan Berhasil di Tambahkan');
    }

    public function show(Jabatan $jabatan)
    {
        //
    }

    public function edit(Jabatan $jabatan)
    {
        return view('master_backend.setting_users.jabatan.edit', [
            'jabatans'      => $jabatan,
            'title'         => 'Halaman Jabatan',
            'title2'        => 'Jabatan',
            'routePrefix'   => 'jabatans'
        ]);
    }


    public function update(Request $request, Jabatan $jabatan)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_jabatan' => 'required'],
            ['nama_jabatan' => 'Nama Jabatan belum diisi']
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage   = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali:', $errorMessage);
        }
        // dd($jabatan);

        $jabatan->update($request->all());
        return redirect()->route('jabatans.index')->with('success', 'Data Struktur Jabatan Berhasil di Update');
    }

    public function destroy($id)
    {

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('jabatans.index')->with('success', 'Data Jabatan Berhasil dihapus.');
    }
}
