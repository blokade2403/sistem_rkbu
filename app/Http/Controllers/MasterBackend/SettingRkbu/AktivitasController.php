<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aktivitas = Aktivitas::with(['program', 'sub_kegiatan'])->get();
        return view('master_backend.setting_rkbu.aktivitas.index', compact('aktivitas'), [
            'title'         => 'Halaman Aktivitas',
            'title2'        => 'Aktivitas',
            'routePrefix'   => 'aktivitas'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $programs       = Program::all();
        $sub_kegiatans  = SubKegiatan::all();
        return view('master_backend.setting_rkbu.aktivitas.create', compact('programs', 'sub_kegiatans'), [
            'title'         => 'Halaman Aktivitas',
            'title2'        => 'Aktivitas',
            'routePrefix'   => 'aktivitas'
        ]);
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
                'id_program'        => 'required',
                'id_sub_kegiatan'   => 'required',
                'nama_aktivitas'    => 'required',
                'kode_aktivitas'    => 'required',
            ],
            [
                'id_program.required'       => 'Id Fungsional harus diisi.',
                'id_sub_kegiatan.required'  => 'Id Pejabat harus diisi.',
                'nama_aktivitas.required'   => 'Nama Aktivitas harus diisi.',
                'kode_aktivitas.required'   => 'Kode Aktivitas harus diisi.',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }


        // Simpan data ke dalam database
        $aktivitas = new Aktivitas([
            'id_program'       => $request->id_program,
            'id_sub_kegiatan'  => $request->id_sub_kegiatan,
            'nama_aktivitas'   => $request->nama_aktivitas,
            'kode_aktivitas'   => $request->kode_aktivitas,
        ]);

        $aktivitas->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Aktivitas $aktivitas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $aktivitas              = Aktivitas::with('sub_kegiatan')->findOrFail($id);
        $programs               = Program::all(); // Mengambil semua data Jabatan
        $sub_kegiatans          = SubKegiatan::with('kegiatan')->get(); // Mengambil semua data Jabatan
        return view('master_backend.setting_rkbu.aktivitas.edit', compact('aktivitas', 'programs', 'sub_kegiatans'), [
            'title'         => 'Halaman Aktivitas',
            'title2'        => 'Aktivitas',
            'routePrefix'   => 'aktivitas'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aktivitas $aktivitas)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_program'            => 'required',
                'id_sub_kegiatan'       => 'required',
                'nama_aktivitas'        => 'required',
                'kode_aktivitas'        => 'required',
            ],
            [
                'id_program.required'               => 'Id Program harus diisi.',
                'id_sub_kegiatan.required'          => 'Id Sub Kegiatan harus diisi.',
                'nama_aktivitas.required'           => 'Nama Aktivitas harus diisi.',
                'kode_aktivitas.required'           => 'Kode Aktivitas harus diisi.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $aktivitas->update($request->all());
        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas Update Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aktivitas $aktivitas)
    {
        $aktivitas->delete();

        return redirect()->route('aktivitas.index')
            ->with('success', 'KSP deleted successfully.');
    }
}
