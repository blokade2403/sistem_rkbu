<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekeningBelanjaController extends Controller
{
    public function index()
    {
        $rekening_belanjas = RekeningBelanja::with('aktivitas')->get();
        $aktivitas = Aktivitas::all();
        return view(
            'master_backend.setting_rkbu.rekening_belanja.index',
            compact('rekening_belanjas', 'aktivitas'),
            [
                'title' => 'Halaman Rekening Belanja',
                'title2' => 'Rekening Belanja',
                'routePrefix' => 'rekening_belanjas',
            ],
        );
    }

    public function create()
    {
        $aktivitas = Aktivitas::all();
        return view('master_backend.setting_rkbu.rekening_belanja.create', compact('aktivitas'), [
            'title' => 'Halaman Rekening Belanja',
            'title2' => 'Rekening Belanja',
            'routePrefix' => 'rekening_belanjas',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_aktivitas' => 'required',
                'kode_rekening_belanja' => 'required',
                'nama_rekening_belanja' => 'required',
            ],
            [
                'id_aktivitas' => 'id_aktivitas required',
                'kode_rekening_belanja' => 'kode_rekening_belanja required',
                'nama_rekening_belanja' => 'nama_rekening_belanja required',
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

        $rekening_belanjas = new RekeningBelanja([
            'id_aktivitas' => $request->id_aktivitas,
            'kode_rekening_belanja' => $request->kode_rekening_belanja,
            'nama_rekening_belanja' => $request->nama_rekening_belanja,
        ]);

        // dd($rekening_belanjas);

        $rekening_belanjas->save();
        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RekeningBelanja $rekeningBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $aktivitas = Aktivitas::all();
        $rekeningBelanja = RekeningBelanja::with('aktivitas')->findOrFail($id);
        return view(
            'master_backend.setting_rkbu.rekening_belanja.edit',
            compact('aktivitas', 'rekeningBelanja'),
            [
                'title' => 'Halaman Rekening Belanja',
                'title2' => 'Rekening Belanja',
                'routePrefix' => 'rekening_belanjas',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningBelanja $rekeningBelanja)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_aktivitas' => 'required',
                'kode_rekening_belanja' => 'required',
                'nama_rekening_belanja' => 'required',
            ],
            [
                'id_aktivitas' => 'id_aktivitas required',
                'kode_rekening_belanja' => 'kode_rekening_belanja required',
                'nama_rekening_belanja' => 'nama_rekening_belanja required',
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

        $rekeningBelanja->update($request->all());
        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rekeningBelanja = RekeningBelanja::findOrFail($id);
        $rekeningBelanja->delete();
        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Hapus');
    }
}
