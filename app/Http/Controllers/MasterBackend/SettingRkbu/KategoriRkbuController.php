<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\ObyekBelanja;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class KategoriRkbuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();
        $kategori_rkbus = KategoriRkbu::with(['obyek_belanja', 'jenis_kategori_rkbu'])->get();
        return view(
            'master_backend.setting_rkbu.kategori_rkbu.index',
            compact('kategori_rkbus', 'jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();
        return view(
            'master_backend.setting_rkbu.kategori_rkbu.create',
            compact('jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu' => 'required',
                'id_obyek_belanja' => 'required',
                'kode_kategori_rkbu' => 'required',
                'nama_kategori_rkbu' => 'required',
            ],
            [
                'id_jenis_kategori_rkbu.required' => 'Id Jenis Kategori RKBU harus diisi.',
                'id_obyek_belanja.required' => 'Id Obyek Belanja harus diisi.',
                'kode_kategori_rkbu.required' => 'Nama kategori_rkbu harus diisi.',
                'nama_kategori_rkbu.required' => 'NIP kategori_rkbu harus diisi.',
            ],
        );

        // if ($validator->fails()) {
        //     $errors = $validator->errors();
        //     dd($errors);
        // }

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        // Simpan data ke dalam database
        $kategori_rkbu = new KategoriRkbu([
            'id_jenis_kategori_rkbu' => $request->id_jenis_kategori_rkbu,
            'id_obyek_belanja' => $request->id_obyek_belanja,
            'kode_kategori_rkbu' => $request->kode_kategori_rkbu,
            'nama_kategori_rkbu' => $request->nama_kategori_rkbu,
        ]);

        // dd($kategori_rkbu);

        $kategori_rkbu->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriRkbu $kategoriRkbu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori_rkbus = KategoriRkbu::with('obyek_belanja')->findOrFail($id);
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();
        return view(
            'master_backend.setting_rkbu.kategori_rkbu.edit',
            compact('kategori_rkbus', 'jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriRkbu $kategoriRkbu)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu' => 'required',
                'id_obyek_belanja' => 'required',
                'kode_kategori_rkbu' => 'required',
                'nama_kategori_rkbu' => 'required',
            ],
            [
                'id_jenis_kategori_rkbu.required' => 'Id Jenis Kategori RKBU harus diisi.',
                'id_obyek_belanja.required' => 'Id Obyek Belanja harus diisi.',
                'kode_kategori_rkbu.required' => 'Nama kategori_rkbu harus diisi.',
                'nama_kategori_rkbu.required' => 'NIP kategori_rkbu harus diisi.',
            ],
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        // dd($request);

        $kategoriRkbu->update($request->all());
        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriRkbu $kategoriRkbu)
    {
        $kategoriRkbu->delete();

        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu deleted successfully.');
    }
}
