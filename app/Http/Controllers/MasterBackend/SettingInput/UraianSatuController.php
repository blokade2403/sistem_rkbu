<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\UraianSatu;

class UraianSatuController extends Controller
{
    public function index()
    {
        $uraian_satus  = UraianSatu::all();
        return view('master_backend.setting_input.uraian_satu.index', compact('uraian_satus'), ['title' => 'Halaman Uraian Satu']);
    }

    public function create()
    {
        return view('master_backend.setting_input.uraian_satu.create', ['title' => 'Halaman Uraian Satu']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_uraian_1' => 'required'],
            ['nama_uraian_1' => 'Nama uraian_satu belum terisi']
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
        $uraian_satu = new UraianSatu(['nama_uraian_1'     => $request->nama_uraian_1]);

        // dd($uraian_satu);

        $uraian_satu->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_satus.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    public function show(UraianSatu $uraianSatu)
    {
        //
    }

    public function edit(UraianSatu $uraianSatu)
    {
        // $uraianSatu = UraianSatu::all();
        return view('master_backend.setting_input.uraian_satu.edit', compact('uraianSatu'), ['title' => 'Halaman Uraian Satu']);
    }

    public function update(Request $request, UraianSatu $uraianSatu)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_uraian_1' => 'required'],
            ['nama_uraian_1' => 'Nama uraian_satu belum terisi']
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
        $uraianSatu->update($request->all());

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_satus.index')->with('success', 'Data uraian_satu berhasil ditambahkan.');
    }

    public function destroy(UraianSatu $uraianSatu)
    {
        $uraianSatu->delete();
        return redirect()->route('uraian_satus.index')->with('success', 'Data Berhasil di Delete');
    }
}
