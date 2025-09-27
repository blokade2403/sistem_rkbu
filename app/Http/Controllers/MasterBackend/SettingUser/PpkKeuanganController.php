<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\PpkKeuangan;

class PpkKeuanganController extends Controller
{

    public function index()
    {
        $ppk_keuangans = PpkKeuangan::all();
        return view('master_backend.setting_users.ppk_keuangan.index', ['ppk_keuangans' => $ppk_keuangans, 'title' => 'PPK Keuangan']);
    }

    public function create()
    {
        return view('master_backend.setting_users.ppk_keuangan.create',  ['title' => 'PPK Keuangan']);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'nama_ppk_keuangan'     => 'required',
                'nip_ppk_keuangan'      =>  'required',
                'status'                =>  'required',
            ],
            [
                'nama_ppk_keuangan'     =>  'Nama PPK Harus diisi',
                'nip_ppk_keuangan'      =>  'Nip PPK Harus diisi',
                'status'                =>  'Status Harus diisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $ppk_keuangans  = new PpkKeuangan(
            [
                'nama_ppk_keuangan'     => $request->nama_ppk_keuangan,
                'nip_ppk_keuangan'      => $request->nip_ppk_keuangan,
                'status'                => $request->status,
            ]
        );
        $ppk_keuangans->save();
        return redirect()->route('ppk_keuangans.index')->with('success', 'Data Berhasil Di Tambah');
    }

    public function show(PpkKeuangan $ppkKeuangan)
    {
        //
    }

    public function edit(PpkKeuangan $ppkKeuangan)
    {
        return view('master_backend.setting_users.ppk_keuangan.edit',  ['ppk_keuangans' => $ppkKeuangan, 'title' => 'PPK Keuangan']);
    }

    public function update(Request $request, PpkKeuangan $ppkKeuangan)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'nama_ppk_keuangan'     => 'required',
                'nip_ppk_keuangan'      =>  'required',
                'status'                =>  'required',
            ],
            [
                'nama_ppk_keuangan'     =>  'Nama PPK Harus diisi',
                'nip_ppk_keuangan'     =>  'Nip PPK Harus diisi',
                'status'                =>  'Status Harus diisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $ppkKeuangan->update($request->all());
        return redirect()->route('ppk_keuangans.index')->with('success', 'Data Berhasil Di Ubah');
    }

    public function destroy(PpkKeuangan $ppkKeuangan)
    {
        //
    }
}
