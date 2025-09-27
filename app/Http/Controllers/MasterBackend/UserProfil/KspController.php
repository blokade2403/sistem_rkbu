<?php

namespace App\Http\Controllers\MasterBackend\UserProfil;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\UserProfil\Ksp;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingUser\Fungsional;

class KspController extends Controller
{
    public function index()
    {
        $ksps = Ksp::with(['fungsional', 'pejabat'])->get();
        return view('master_backend.user_profil.ksp.index', compact('ksps'), ['title'   => 'Halaman KSP']);
    }

    public function create()
    {

        $pejabats       = Pejabat::where('id_jabatan', '!=', 1)->get();
        $fungsionals    = Fungsional::all();
        return view('master_backend.user_profil.ksp.create', compact('pejabats', 'fungsionals'), ['title'   => 'Halaman KSP']);
    }


    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_fungsional' => 'required',
                'id_pejabat'    => 'required',
                'nama_ksp'      => 'required',
                'nip_ksp'       => 'required|numeric',
                'status'        => 'required',
            ],
            [
                'id_fungsional.required'    => 'Id Fungsional harus diisi.',
                'id_pejabat.required'       => 'Id Pejabat harus diisi.',
                'nama_ksp.required'         => 'Nama KSP harus diisi.',
                'nip_ksp.required'          => 'NIP KSP harus diisi.',
                'nip_ksp.numeric'           => 'NIP KSP harus berupa angka.',
                'status.required'           => 'Status harus diisi.',
                'status.required'           => 'Status harus salah satu dari: aktif atau tidak aktif.',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Simpan data ke dalam database
        $ksp = new Ksp([
            'id_fungsional' => $request->id_fungsional,
            'id_pejabat'    => $request->id_pejabat,
            'nama_ksp'      => $request->nama_ksp,
            'nip_ksp'       => $request->nip_ksp,
            'status'        => $request->status,
        ]);

        // dd($ksp);

        $ksp->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('ksps.index')->with('success', 'KSP berhasil ditambahkan.');
    }


    public function show(Ksp $ksp)
    {
        //
    }

    public function edit($id)
    {
        $ksps           = Ksp::with('pejabat')->findOrFail($id);
        $fungsionals    = Fungsional::all(); // Mengambil semua data Jabatan
        $pejabats       = Pejabat::all(); // Mengambil semua data Jabatan
        return view('master_backend.user_profil.ksp.edit', compact('ksps', 'fungsionals', 'pejabats'), ['title'   => 'Halaman KSP']);
    }

    public function update(Request $request, Ksp $ksp)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_fungsional' => 'required',
                'id_pejabat'    => 'required',
                'nama_ksp'      => 'required',
                'nip_ksp'       => 'required|numeric',
                'status'        => 'required',
            ],
            [
                'id_fungsional.required'    => 'Id Fungsional harus diisi.',
                'id_pejabat.required'       => 'Id Pejabat harus diisi.',
                'nama_ksp.required'         => 'Nama KSP harus diisi.',
                'nip_ksp.required'          => 'NIP KSP harus diisi.',
                'nip_ksp.numeric'           => 'NIP KSP harus berupa angka.',
                'status.required'           => 'Status harus diisi.',
                'status.required'           => 'Status harus salah satu dari: aktif atau tidak aktif.',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // dd($request);

        $ksp->update($request->all());
        return redirect()->route('ksps.index')->with('success', 'KSP Update Success');
    }

    public function destroy(Ksp $ksp)
    {
        $ksp->delete();

        return redirect()->route('ksps.index')
            ->with('success', 'KSP deleted successfully.');
    }
}
