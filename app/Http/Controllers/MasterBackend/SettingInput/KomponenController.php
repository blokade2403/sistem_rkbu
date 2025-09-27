<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\DataTables\KomponenDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class KomponenController extends Controller
{
    public function index(KomponenDataTable $dataTable)
    {
        return $dataTable->render('master_backend.setting_input.komponen.index', [
            'title' => 'Halaman Komponen'
        ]);
    }

    public function create()
    {
        $jenis_kategori_rkbu    = JenisKategoriRkbu::all();
        return view('master_backend.setting_input.komponen.create', compact('jenis_kategori_rkbu'), ['title' => 'Halaman Komponen']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'    => 'required',
                'kode_barang'               => 'required',
                'kode_komponen'             => 'required',
                'nama_barang'               => 'required',
                'satuan'                    => 'required',
                'spek'                      => 'required',
                'harga_barang'              => 'required',
            ],
            [
                'id_jenis_kategori_rkbu'    => 'Nama komponen belum terisi',
                'kode_barang'               => 'Nama komponen belum terisi',
                'kode_komponen'             => 'Nama komponen belum terisi',
                'nama_barang'               => 'Nama komponen belum terisi',
                'satuan'                    => 'Nama komponen belum terisi',
                'spek'                      => 'Nama komponen belum terisi',
                'harga_barang'              => 'Nama komponen belum terisi',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Simpan data ke dalam database
        $komponen = new Komponen(
            [
                'id_jenis_kategori_rkbu'        => $request->id_jenis_kategori_rkbu,
                'kode_barang'                   => $request->kode_barang,
                'kode_komponen'                 => $request->kode_komponen,
                'nama_barang'                   => $request->nama_barang,
                'satuan'                        => $request->satuan,
                'spek'                          => $request->spek,
                'harga_barang'                  => $request->harga_barang,

            ]
        );

        // dd($komponen);

        $komponen->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('komponens.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Komponen $komponen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $komponen = Komponen::with('jenis_kategori_rkbu')->findOrFail($id);
        $jenis_kategori_rkbu    = JenisKategoriRkbu::all();
        return view('backend.setting_input.komponens.edit', compact('komponen', 'jenis_kategori_rkbu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Komponen $komponen)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'    => 'required',
                'kode_barang'               => 'required',
                'kode_komponen'             => 'required',
                'nama_barang'               => 'required',
                'satuan'                    => 'required',
                'spek'                      => 'required',
                'harga_barang'              => 'required',
            ],
            [
                'id_jenis_kategori_rkbu' => 'Nama komponen belum terisi',
                'kode_barang' => 'Nama komponen belum terisi',
                'kode_komponen' => 'Nama komponen belum terisi',
                'nama_barang' => 'Nama komponen belum terisi',
                'satuan' => 'Nama komponen belum terisi',
                'spek' => 'Nama komponen belum terisi',
                'harga_barang' => 'Nama komponen belum terisi',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }


        // dd($komponen);
        $komponen->update($request->all());

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('komponens.index')->with('success', 'Data komponen berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Komponen $komponen)
    {
        $komponen->delete();

        return redirect()->route('komponens.index')
            ->with('success', 'Komponen deleted successfully.');
    }
}
