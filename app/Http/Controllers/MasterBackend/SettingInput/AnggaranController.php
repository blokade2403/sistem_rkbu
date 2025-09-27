<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;

class AnggaranController extends Controller
{

    public function index()
    {
        $anggarans  = Anggaran::all();
        return view('master_backend.setting_input.anggaran.index', compact('anggarans'), ['title' => 'Halaman Anggaran']);
    }

    public function create()
    {
        $rekening_belanjas  = RekeningBelanja::all();
        $sumber_danas       = SumberDana::all();
        $tahun_anggaran     = TahunAnggaran::all();

        // dd($rekening_belanjas);

        return view('backend.master_setting.anggarans.create', compact('rekening_belanjas', 'sumber_danas', 'tahun_anggaran'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_kode_rekening_belanja'  => 'required',
                'id_sumber_dana'            => 'required',
                'nama_anggaran'             => 'required',
                'jumlah_anggaran'           => 'required',
                'tahun_anggaran'            => 'required',
            ],
            [
                'id_kode_rekening_belanja'  => 'id_kode_rekening_belanja harus diisi',
                'id_sumber_dana'            => 'id_sumber_dana harus diisi',
                'nama_anggaran'             => 'nama_anggaran harus diisi',
                'jumlah_anggaran'           => 'jumlah_anggaran harus diisi',
                'tahun_anggaran'            => 'tahun_anggaran harus diisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Isian Data Belum Lengkap ' . $errorMessage);
        }

        $anggarans = new Anggaran();

        $anggarans->id_kode_rekening_belanja    = $request->input('id_kode_rekening_belanja');
        $anggarans->id_sumber_dana              = $request->input('id_sumber_dana');
        $anggarans->nama_anggaran               = $request->input('nama_anggaran');
        $anggarans->jumlah_anggaran             = $request->input('jumlah_anggaran');
        $anggarans->tahun_anggaran              = $request->input('tahun_anggaran');

        // dd($anggarans);
        $anggarans->save();
        return redirect()->route('anggarans.index')->with('success', 'Data Berhasil Di Tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Anggaran $anggaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $anggaran  = Anggaran::with('rekening_belanjas', 'sumber_dana')->findOrFail($id);
        $rekening_belanjas = RekeningBelanja::all();
        $sumber_danas = SumberDana::all();
        $tahun_anggaran = TahunAnggaran::all();
        return view('backend.master_setting.anggarans.edit', compact('anggaran', 'rekening_belanjas', 'sumber_danas', 'tahun_anggaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'id_kode_rekening_belanja'  => 'required',
                'id_sumber_dana'            => 'required',
                'nama_anggaran'             => 'required',
                'jumlah_anggaran'           => 'required|numeric',
                'tahun_anggaran'            => 'required|digits:4|integer',
            ],
            [
                'id_kode_rekening_belanja.required'  => 'Kode rekening belanja harus diisi',
                'id_sumber_dana.required'            => 'Sumber dana harus diisi',
                'nama_anggaran.required'             => 'Nama anggaran harus diisi',
                'jumlah_anggaran.required'           => 'Jumlah anggaran harus diisi',
                'jumlah_anggaran.numeric'            => 'Jumlah anggaran harus berupa angka',
                'tahun_anggaran.required'            => 'Tahun anggaran harus diisi',
                'tahun_anggaran.digits'              => 'Tahun anggaran harus terdiri dari 4 digit',
                'tahun_anggaran.integer'             => 'Tahun anggaran harus berupa angka',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Isian Data Belum Lengkap: ' . $errorMessage);
        }

        $anggaran = Anggaran::find($id);

        $anggaran->id_kode_rekening_belanja     = $request->input('id_kode_rekening_belanja');
        $anggaran->id_sumber_dana               = $request->input('id_sumber_dana');
        $anggaran->nama_anggaran                = $request->input('nama_anggaran');
        $anggaran->jumlah_anggaran              = $request->input('jumlah_anggaran');
        $anggaran->tahun_anggaran               = $request->input('tahun_anggaran');

        $anggaran->save();


        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('anggarans.index')->with('success', 'Data berhasil diupdate');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggaran $anggaran)
    {

        $anggaran->delete();

        return redirect()->route('anggarans.index')
            ->with('success', 'KSP deleted successfully.');
    }
}
