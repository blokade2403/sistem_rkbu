<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class StatusValidasiRkaController extends Controller
{
    public function index()
    {
        $status_validasi_rkas = StatusValidasiRka::all();
        return view(
            'master_backend.setting_input.status_validasi_rka.index',
            compact('status_validasi_rkas'),
            [
                'title' => 'Halaman Status Validasi',
                'title2' => 'Status Validasi',
                'routePrefix' => 'status_validasi_rkas',
            ],
        );
    }

    public function create()
    {
        return view('master_backend.setting_input.status_validasi_rka.create', [
            'title' => 'Halaman Status Validasi',
            'title2' => 'Status Validasi',
            'routePrefix' => 'status_validasi_rkas',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_validasi_rka' => 'required',
            ],
            [
                'nama_validasi_rka' => 'Nama Status Validasi Tidak Boleh Kosong',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        $status_validasi_rkas = new StatusValidasiRka();
        $status_validasi_rkas->nama_validasi_rka = $request->nama_validasi_rka;
        $status_validasi_rkas->save();

        // Set session flash
        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $obj = StatusValidasiRka::findOrFail($id);
        return view('backend.setting_input.status_validasi.show', compact('obj'));
    }

    public function edit(StatusValidasiRka $statusValidasiRka)
    {
        return view('master_backend.setting_input.status_validasi_rka.edit', [
            'statusValidasiRka' => $statusValidasiRka,
            'title' => 'Halaman Status Validasi',
            'title2' => 'Status Validasi',
            'routePrefix' => 'status_validasi_rkas',
        ]);
    }

    public function update(Request $request, StatusValidasiRka $statusValidasiRka)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_validasi_rka' => 'required',
            ],
            [
                'nama_validasi_rka' => 'Nama Status Validasi Tidak Boleh Kosong',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        // dd($request->all());

        $statusValidasiRka->update($request->all());
        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy(StatusValidasiRka $statusValidasiRka)
    {
        $statusValidasiRka->delete();
        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data Berhasil di Hapus');
    }
}
