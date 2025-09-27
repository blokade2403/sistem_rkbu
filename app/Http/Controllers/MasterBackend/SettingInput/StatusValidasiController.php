<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\StatusValidasi;

class StatusValidasiController extends Controller
{
    public function index()
    {
        $status_validasis   = StatusValidasi::all();
        return view('master_backend.setting_input.status_validasi.index', compact('status_validasis'), ['title' => 'Halaman Status Validasi']);
    }

    public function create()
    {
        return view('master_backend.setting_input.status_validasi.create', ['title' => 'Halaman Status Validasi']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_validasi'  => 'required',
            ],
            [
                'nama_validasi'     => 'Nama Status Validasi Tidak Boleh Kosong'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $status_validasis = new StatusValidasi();
        $status_validasis->nama_validasi = $request->nama_validasi;
        $status_validasis->save();

        // Set session flash
        return redirect()->route('status_validasis.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }


    public function show($id)
    {
        $obj = StatusValidasi::findOrFail($id);
        return view('backend.setting_input.status_validasi.show', compact('obj'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusValidasi $statusValidasi)
    {
        return view('master_backend.setting_input.status_validasi.edit', ['statusValidasi' => $statusValidasi, 'title' => 'Halaman Status Validasi']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatusValidasi $statusValidasi)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_validasi'  => 'required',
            ],
            [
                'nama_validasi'     => 'Nama Status Validasi Tidak Boleh Kosong'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $statusValidasi->update($request->all());
        return redirect()->route('status_validasis.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusValidasi $statusValidasi)
    {
        $statusValidasi->delete();
        return redirect()->route('status_validasis.index')->with('success', 'Data Berhasil di Hapus');
    }
}
