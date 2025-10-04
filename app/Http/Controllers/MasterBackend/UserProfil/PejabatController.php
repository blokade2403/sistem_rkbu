<?php

namespace App\Http\Controllers\MasterBackend\UserProfil;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingUser\Jabatan;

class PejabatController extends Controller
{

    public function index()
    {
        $pejabats = Pejabat::with('jabatan')->get();
        return view('master_backend.user_profil.pejabat.index', compact('pejabats'), [
            'title'             => 'Halaman Pejabat',
            'title2'            => 'Pejabat',
            'routePrefix'       => 'pejabats'
        ]);
    }


    public function create()
    {
        $jabatans = Jabatan::all();
        return view('master_backend.user_profil.pejabat.create', compact('jabatans'), [
            'title'             => 'Halaman Pejabat',
            'title2'            => 'Pejabat',
            'routePrefix'       => 'pejabats'
        ]);
    }


    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_jabatan'    => 'required',
                'nama_pejabat'  => 'required',
                'nip_pejabat'   => 'required|numeric',
                'status'        => 'required|in:aktif,tidak aktif',
            ],
            [
                'id_jabatan.required'       => 'Id Jabatan harus diisi.',
                'nama_pejabat.required'     => 'Nama Pejabat harus diisi.',
                'nip_pejabat.required'      => 'NIP Pejabat harus diisi.',
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
        $Pejabat = new Pejabat([
            'id_jabatan'        => $request->id_jabatan,
            'nama_pejabat'      => $request->nama_pejabat,
            'nip_pejabat'       => $request->nip_pejabat,
            'status'            => $request->status,
        ]);

        $Pejabat->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('pejabats.index')->with('success', 'Nama Pejabat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pejabat $pejabat)
    {
        return view('backend.user_profile.pejabats.show', compact('pejabat'), ['title' => 'Pejabat']);
    }

    public function edit($id)
    {
        $pejabat = Pejabat::with('jabatan')->findOrFail($id);
        $jabatans = Jabatan::all();

        return view('master_backend.user_profil.pejabat.edit', compact('pejabat', 'jabatans'), [
            'title'       => 'Halaman Pejabat',
            'title2'      => 'Pejabat',
            'routePrefix' => 'pejabats'
        ]);
    }


    public function update(Request $request, Pejabat $pejabat)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_jabatan'    => 'required',
                'nama_pejabat'  => 'required',
                'nip_pejabat'   => 'required|numeric',
                'status'        => 'required|in:aktif,tidak aktif',
            ],
            [
                'id_jabatan.required'       => 'Id Jabatan harus diisi.',
                'nama_pejabat.required'     => 'Nama Pejabat harus diisi.',
                'nip_pejabat.required'      => 'NIP Pejabat harus diisi.',
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

        $pejabat->update($request->all());
        return redirect()->route('pejabats.index')->with('success', 'Pejabat Update Success');
    }

    public function destroy(Pejabat $pejabat)
    {
        $pejabat->delete();

        return redirect()->route('pejabats.index')
            ->with('success', 'Pejabat deleted successfully.');
    }
}
