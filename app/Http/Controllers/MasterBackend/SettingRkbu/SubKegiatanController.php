<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;

class SubKegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::all();
        $sumber_danas = SumberDana::all();
        $sub_kegiatans = SubKegiatan::with(['kegiatan', 'sumber_danas'])->get();
        return view(
            'master_backend.setting_rkbu.sub_kegiatan.index',
            compact('sub_kegiatans', 'kegiatans', 'sumber_danas'),
            [
                'title' => 'Halaman Sub Kegiatan',
                'title2' => 'Sub Kegiatan',
                'routePrefix' => 'sub_kegiatans',
            ],
        );
    }

    public function create()
    {
        $kegiatans = Kegiatan::all();
        $sumber_danas = SumberDana::all();
        return view(
            'master_backend.setting_rkbu.sub_kegiatan.create',
            compact('kegiatans', 'sumber_danas'),
            [
                'title' => 'Halaman Sub Kegiatan',
                'title2' => 'Sub Kegiatan',
                'routePrefix' => 'sub_kegiatans',
            ],
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_kegiatan' => 'required',
                'kode_sub_kegiatan' => 'required',
                'nama_sub_kegiatan' => 'required',
                'id_sumber_dana' => 'required',
                'tujuan_sub_kegiatan' => 'required',
                'indikator_sub_kegiatan' => 'required',
            ],
            [
                'id_kegiatan.required' => 'ID Program Harus di Pilih',
                'kode_sub_kegiatan.required' => 'Kode Kegiatan Harus Diisi',
                'id_sumber_dana.required' => 'Nama Kegiatan harus diisi',
                'tujuan_sub_kegiatan.required' => 'Nama Kegiatan harus diisi',
                'indikator_sub_kegiatan.required' => 'Nama Kegiatan harus diisi',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            // dd($errors);
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        $kegiatan = new SubKegiatan([
            'id_kegiatan' => $request->id_kegiatan,
            'kode_sub_kegiatan' => $request->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'id_sumber_dana' => $request->id_sumber_dana,
            'tujuan_sub_kegiatan' => $request->tujuan_sub_kegiatan,
            'indikator_sub_kegiatan' => $request->indikator_sub_kegiatan,
        ]);

        // dd($kegiatan);

        $kegiatan->save();
        return redirect()
            ->route('sub_kegiatans.index')
            ->with('success', 'Data Kegiatan Berhasil di Tambahkan');
    }

    public function show(SubKegiatan $sub_kegiatan)
    {
        //
    }

    public function edit($id)
    {
        $sub_kegiatans = SubKegiatan::with('kegiatan')->findOrFail($id);
        $sumber_danas = SumberDana::all(); // Mengambil semua data Jabatan
        $kegiatans = Kegiatan::all();
        return view(
            'master_backend.setting_rkbu.sub_kegiatan.edit',
            compact('sub_kegiatans', 'sumber_danas', 'kegiatans'),
            [
                'title' => 'Halaman Sub Kegiatan',
                'title2' => 'Sub Kegiatan',
                'routePrefix' => 'sub_kegiatans',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKegiatan $sub_kegiatan)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_kegiatan' => 'required',
                'kode_sub_kegiatan' => 'required',
                'nama_sub_kegiatan' => 'required',
                'id_sumber_dana' => 'required',
                'tujuan_sub_kegiatan' => 'required',
                'indikator_sub_kegiatan' => 'required',
            ],
            [
                'id_kegiatan.required' => 'ID Program Harus di Pilih',
                'kode_sub_kegiatan.required' => 'Kode Kegiatan Harus Diisi',
                'id_sumber_dana.required' => 'Nama Kegiatan harus diisi',
                'tujuan_sub_kegiatan.required' => 'Nama Kegiatan harus diisi',
                'indikator_sub_kegiatan.required' => 'Nama Kegiatan harus diisi',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            // dd($errors);
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }
        // dd($request);

        $sub_kegiatan->update($request->all());
        return redirect()
            ->route('sub_kegiatans.index')
            ->with('success', 'Sub Kegiatan Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKegiatan $sub_kegiatan)
    {
        //
    }
}
