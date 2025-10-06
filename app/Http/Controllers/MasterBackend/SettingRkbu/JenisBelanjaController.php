<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;

class JenisBelanjaController extends Controller
{
    public function index(Request $request)
    {
        $jenis_belanjas  = JenisBelanja::all();
        return view('master_backend.setting_rkbu.jenis_belanja.index', compact('jenis_belanjas'), [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_belanjas'
        ]);
    }


    public function create()
    {

        return view('master_backend.setting_rkbu.jenis_belanja.create', [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_belanjas'
        ]);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            ['kode_jenis_belanja'   => 'required'],
            ['nama_jenis_belanja'   => 'Nama Harud Diisi']
        );

        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();
            // dd($errors);
            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $jenis_belanja = new JenisBelanja(
            [
                'kode_jenis_belanja'   => $request->kode_jenis_belanja,
                'nama_jenis_belanja'   => $request->nama_jenis_belanja,
            ]
        );

        // dd($jenis_belanja);
        $jenis_belanja->save();
        return redirect()->route('jenis_belanjas.index')->with('success', 'Nama jenis_belanja Delete Success');
    }

    public function show(JenisBelanja $jenisBelanja)
    {
        //
    }

    public function edit(JenisBelanja $jenisBelanja)
    {
        return view('master_backend.setting_rkbu.jenis_belanja.edit', compact('jenisBelanja'), [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_belanjas'
        ]);
    }

    public function update(Request $request, JenisBelanja $jenisBelanja)
    {
        $validator  = Validator::make(
            $request->all(),
            ['kode_jenis_belanja' => 'required'],
            ['nama_jenis_belanja'  => 'Nama Harud Diisi']
        );

        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();
            // dd($errors);
            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }
        // dd($request);

        $jenisBelanja->update($request->all());
        return redirect()->route('jenis_belanjas.index')->with('success', 'Nama level_user Delete Success');
    }

    public function destroy(JenisBelanja $jenisBelanja)
    {
        $jenisBelanja->delete();
        return redirect()->route('jenis_belanjas.index')
            ->with('success', 'Jenis Belanja deleted successfully.');
    }
}
