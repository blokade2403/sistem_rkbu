<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        $kegiatans = Kegiatan::with('program')->get();
        return view(
            'master_backend.setting_rkbu.kegiatan.index',
            compact('kegiatans', 'programs'),
            [
                'title' => 'Halaman Kegiatan',
                'title2' => 'Kegiatan',
                'routePrefix' => 'kegiatans',
            ],
        );
    }

    public function create()
    {
        $programs = Program::all();
        return view('master_backend.setting_rkbu.kegiatan.create', compact('programs'), [
            'title' => 'Halaman Kegiatan',
            'title2' => 'Kegiatan',
            'routePrefix' => 'kegiatans',
        ]);
    }

    public function getProgramDetails($id)
    {
        $kegiatan = Kegiatan::with('program')->find($id);

        if ($kegiatan) {
            return response()->json([
                'kode_program' => $kegiatan->program->kode_program,
                'nama_program' => $kegiatan->program->nama_program,
            ]);
        }

        return response()->json(['error' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_program' => 'required',
                'kode_kegiatan' => 'required',
                'nama_kegiatan' => 'required',
            ],
            [
                'id_program.required' => 'ID Program Harus di Pilih',
                'kode_kegiatan.required' => 'Kode Kegiatan Harus Diisi',
                'nama_kegiatan.required' => 'Nama Kegiatan harus diisi',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->error();

            $errorMessages = implode(' ', $errors->all());
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages,
                );
        }

        $kegiatan = new Kegiatan([
            'id_program' => $request->id_program,
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        $kegiatan->save();
        return redirect()
            ->route('kegiatans.index')
            ->with('success', 'Data Kegiatan Berhasil di Tambahkan');
    }

    public function show(Kegiatan $kegiatan) {}

    public function edit($id)
    {
        $kegiatans = Kegiatan::with('program')->findOrFail($id);
        $programs = Program::all();
        return view('master_backend.setting_rkbu.kegiatan.edit', compact('kegiatans', 'programs'), [
            'title' => 'Halaman Kegiatan',
            'title2' => 'Kegiatan',
            'routePrefix' => 'kegiatans',
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validator = Validator::make($request->all(), [
            'kode_kegiatan' => 'required',
            'nama_kegiatan' => 'required',
        ]);
        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // dd($errors);

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

        $kegiatan->update($request->all());
        return redirect()->route('kegiatans.index')->with('success', 'Kegiatan Update Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()
            ->route('kegiatans.index')
            ->with('success', 'Kegiatan deleted successfully.');
    }
}
