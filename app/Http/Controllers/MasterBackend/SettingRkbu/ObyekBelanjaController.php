<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\ObyekBelanja;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class ObyekBelanjaController extends Controller
{
    public function index()
    {
        $obyek_belanjas   = ObyekBelanja::all();
        return view('master_backend.setting_rkbu.obyek_belanja.index', compact('obyek_belanjas'), [
            'title'         => 'Halaman Obyek Belanja',
            'title2'        => 'Obyek Belanja',
            'routePrefix'   => 'obyek_belanjas'
        ]);
    }

    public function create()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        return view('master_backend.setting_rkbu.obyek_belanja.create', compact('jenis_kategori_rkbus'), [
            'title'         => 'Halaman Obyek Belanja',
            'title2'        => 'Obyek Belanja',
            'routePrefix'   => 'obyek_belanjas'
        ]);
    }

    public function store(Request $request)
    {
        $validator      = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'        => 'required',
                'kode_obyek_belanja'            => 'required',
                'nama_obyek_belanja'            => 'required',
            ],
            [
                'id_jenis_kategori_rkbu'        => 'id_jenis_kategori_rkbu required',
                'kode_obyek_belanja'            => 'kode_obyek_belanja required',
                'nama_obyek_belanja'            => 'nama_obyek_belanja required',
            ]
        );



        if ($validator->fails()) {
            $errors  = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Periksa Kembali Inputan Anda!!' . $errorMessages);
        }

        $obyek_belanjas = new ObyekBelanja(
            [
                'id_jenis_kategori_rkbu'        => $request->id_jenis_kategori_rkbu,
                'kode_obyek_belanja'            => $request->kode_obyek_belanja,
                'nama_obyek_belanja'            => $request->nama_obyek_belanja,
            ]
        );

        // dd($obyek_belanjas);

        $obyek_belanjas->save();
        return redirect()->route('obyek_belanjas.index')->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ObyekBelanja $obyekBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_kategori_rkbus   = JenisKategoriRkbu::all();
        $obyekBelanja           = ObyekBelanja::with('jenis_kategori_rkbu')->findOrFail($id);
        return view('master_backend.setting_rkbu.obyek_belanja.edit', compact('jenis_kategori_rkbus', 'obyekBelanja'), [
            'title'         => 'Halaman Obyek Belanja',
            'title2'        => 'Obyek Belanja',
            'routePrefix'   => 'obyek_belanjas'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ObyekBelanja $obyekBelanja)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'        => 'required',
                'kode_obyek_belanja'            => 'required',
                'nama_obyek_belanja'            => 'required',
            ],
            [
                'id_jenis_kategori_rkbu'        => 'id_jenis_kategori_rkbu required',
                'kode_obyek_belanja'            => 'kode_obyek_belanja required',
                'nama_obyek_belanja'            => 'nama_obyek_belanja required',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors();
            $errorMessages  = implode(' ', $error->all());
            return redirect()->back()->withInput()->with('error', 'Periksa Kembali Updatean Data', $errorMessages);
        }

        // dd($request);

        $obyekBelanja->update($request->all());
        return redirect()->route('obyek_belanjas.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ObyekBelanja $obyekBelanja)
    {
        $obyekBelanja->delete();
        return redirect()->route('obyek_belanjas.index')->with('success', 'Data Berhasil di Hapus');
    }
}
