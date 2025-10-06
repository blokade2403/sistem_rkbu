<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

use function Pest\Laravel\delete;

class JenisKategoriRkbuController extends Controller
{
    public function index()
    {
        $jenis_kategori_rkbus   = JenisKategoriRkbu::all();
        return view('master_backend.setting_rkbu.jenis_kategori_rkbu.index', compact('jenis_kategori_rkbus'), [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_kategori_rkbus'
        ]);
    }

    public function create()
    {
        $jenis_belanja = JenisBelanja::all();
        return view('master_backend.setting_rkbu.jenis_kategori_rkbu.create', compact('jenis_belanja'), [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_kategori_rkbus'
        ]);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'id_jenis_belanja'          => 'required',
                'kode_jenis_kategori_rkbu'  => 'required',
                'nama_jenis_kategori_rkbu'  => 'required'
            ],
            [
                'id_jenis_belanja'          => 'Jenis Belanja Harud Diisi',
                'kode_jenis_kategori_rkbu'  => 'Kode Harud Diisi',
                'nama_jenis_kategori_rkbu'  => 'Nama Harud Diisi',

            ]
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

        $jenis_kategori_rkbu = new JenisKategoriRkbu(
            [
                'id_jenis_belanja'           => $request->id_jenis_belanja,
                'kode_jenis_kategori_rkbu'   => $request->kode_jenis_kategori_rkbu,
                'nama_jenis_kategori_rkbu'   => $request->nama_jenis_kategori_rkbu,
            ]
        );

        $jenis_kategori_rkbu->save();
        return redirect()->route('jenis_kategori_rkbus.index')->with('success', 'Nama jenis_kategori_rkbu Delete Success');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $jenis_belanja = JenisBelanja::all();
        return view('master_backend.setting_rkbu.jenis_kategori_rkbu.edit', compact('jenisKategoriRkbu', 'jenis_belanja'), [
            'title'         => 'Halaman Jenis Belanja',
            'title2'        => 'Jenis Belanja',
            'routePrefix'   => 'jenis_kategori_rkbus'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $validator  = Validator::make(
            $request->all(),
            ['id_jenis_belanja' => 'required'],
            ['kode_jenis_kategori_rkbu' => 'required'],
            ['nama_jenis_kategori_rkbu'  => 'Nama Harud Diisi']
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

        $jenisKategoriRkbu->update($request->all());
        return redirect()->route('jenis_kategori_rkbus.index')->with('success', 'Nama level_user Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $jenisKategoriRkbu->delete();
        return redirect('jenis_kategori_rkbus.index')->with('success', 'Delete Success');
    }
}
