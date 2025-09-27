<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\UraianDua;

class UraianDuaController extends Controller
{

    public function index()
    {
        $uraian_duas  = UraianDua::all();
        return view('master_backend.setting_input.uraian_dua.index', compact('uraian_duas'), ['title' => 'Halaman Uraian Satu']);
    }

    public function create()
    {
        return view('master_backend.setting_input.uraian_dua.create', ['title' => 'Halaman Uraian Satu']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_uraian_2' => 'required'],
            ['nama_uraian_2' => 'Nama uraian_satu belum terisi']
        );

        if ($validator->fails()) {
            $errors = $validator->error();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Simpan data ke dalam database
        $uraian_satu = new UraianDua(['nama_uraian_2'     => $request->nama_uraian_2]);

        // dd($uraian_satu);

        $uraian_satu->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_duas.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UraianDua $uraianDua)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UraianDua $uraianDua)
    {
        return view('master_backend.setting_input.uraian_dua.edit', ['uraianDua' => $uraianDua, 'title' => 'Halaman Uraian Satu']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UraianDua $uraianDua)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_uraian_2' => 'required'],
            ['nama_uraian_2' => 'Nama uraian_satu belum terisi']
        );

        if ($validator->fails()) {
            $errors = $validator->error();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }


        // dd($uraian_satu);
        $uraianDua->update($request->all());

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_duas.index')->with('success', 'Data uraian_satu berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UraianDua $uraianDua)
    {
        $uraianDua->delete();
        return redirect()->route('uraian_duas.index')->with('success', 'Uraian Delete Berhasil');
    }
}
