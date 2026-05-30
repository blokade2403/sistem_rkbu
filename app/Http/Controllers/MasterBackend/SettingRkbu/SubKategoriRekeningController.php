<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\KategoriRekening;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubKategoriRekeningController extends Controller
{
    public function index()
    {
        $sub_kategori_rekenings = SubKategoriRekening::with('kategori_rekening')->get();
        $kategori_rekenings = KategoriRekening::all();
        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.index',
            compact('sub_kategori_rekenings', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    public function create()
    {
        $kategori_rekenings = KategoriRekening::all();
        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.create',
            compact('sub_kategori_rekening', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_kategori_rekening' => 'required',
                'kode_sub_kategori_rekening' => 'required',
                'nama_sub_kategori_rekening' => 'required',
            ],
            [
                'id_kategori_rekening' => 'id_kategori_rekening required',
                'kode_sub_kategori_rekening' => 'kode_sub_kategori_rekening required',
                'nama_sub_kategori_rekening' => 'nama_sub_kategori_rekening required',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Periksa Kembali Inputan Anda!!' . $errorMessages);
        }

        $sub_kategori_rekening = new SubKategoriRekening([
            'id_kategori_rekening' => $request->id_kategori_rekening,
            'kode_sub_kategori_rekening' => $request->kode_sub_kategori_rekening,
            'nama_sub_kategori_rekening' => $request->nama_sub_kategori_rekening,
        ]);

        // dd($sub_kategori_rekening);

        $sub_kategori_rekening->save();
        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKategoriRekening $subKategoriRekening)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sub_kategori_rekening = SubKategoriRekening::findOrFail($id);
        $kategori_rekenings = KategoriRekening::all();
        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.edit',
            compact('sub_kategori_rekening', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKategoriRekening $subKategoriRekening)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_kategori_rekening' => 'required',
                'kode_sub_kategori_rekening' => 'required',
                'nama_sub_kategori_rekening' => 'required',
            ],
            [
                'id_kategori_rekening' => 'id_kategori_rekening required',
                'kode_sub_kategori_rekening' => 'kode_sub_kategori_rekening required',
                'nama_sub_kategori_rekening' => 'nama_sub_kategori_rekening required',
            ],
        );

        if ($validator->fails()) {
            $error = $validator->errors();
            $errorMessages = implode(' ', $error->all());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Periksa Kembali Updatean Data', $errorMessages);
        }

        // dd($request);

        $subKategoriRekening->update($request->all());
        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sub_kategori_rekenings = SubKategoriRekening::findOrFail($id);
        $sub_kategori_rekenings->delete();
        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Hapus');
    }
}
