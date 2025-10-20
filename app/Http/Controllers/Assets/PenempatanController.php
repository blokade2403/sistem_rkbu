<?php

namespace App\Http\Controllers\Assets;

use Illuminate\Http\Request;
use App\Models\Asset\Penempatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PenempatanController extends Controller
{

    public function index()
    {
        $penempatans    = Penempatan::all();
        return view('asset.penempatan.index', compact('penempatans'), ['title2' => 'Lokasi Barang', 'title' => 'Halaman Lokasi Barang', 'routePrefix' => 'penempatans']);
    }


    public function create()
    {
        return view('asset.penempatan.create', ['title2' => 'Lokasi Barang', 'title' => 'Halaman Lokasi Barang', 'routePrefix' => 'penempatans']);
    }

    public function store(Request $request)
    {
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }
        $validator = Validator::make(
            $request->all(),
            [
                'lokasi_barang'             => 'required|string|max:500',
                'tempat_lokasi'             => 'required|string|max:500',
                'gedung'                    => 'nullable',
                'penanggung_jawab'          => 'nullable',
            ],
            [
                'lokasi_barang'             => 'Lokasi Barang wajib diisi.',
                'tempat_lokasi'             => 'Tempat Barang wajib diisi.',
                'gedung'                    => 'nullable',
                'penanggung_jawab'          => 'nullable',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $penempatans = new Penempatan([
            'lokasi_barang'         =>  $request->lokasi_barang,
            'gedung'                =>  $request->gedung,
            'tempat_lokasi'         =>  $request->tempat_lokasi,
            'penanggung_jawab'      =>  $request->penanggung_jawab,
        ]);

        // dd($penempatans);

        $penempatans->save();

        // Redirect dengan pesan sukses
        return redirect()->route('penempatans.index')->with('success', 'Data Berhasil Ditambahkan');
    }


    public function show(Penempatan $penempatan)
    {
        //
    }


    public function edit($id)
    {
        $penempatans   = Penempatan::findOrFail($id);
        return view('asset.penempatan.edit', compact('penempatans'), ['title2' => 'Lokasi Barang', 'title' => 'Halaman Lokasi Barang', 'routePrefix' => 'penempatans']);
    }


    public function update(Request $request, Penempatan $penempatan)
    {
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }
        $validator = Validator::make(
            $request->all(),
            [
                'lokasi_barang'  => 'required|string|max:500',
                'tempat_lokasi'  => 'required|string|max:500',
                'gedung'         => 'nullable',
                'penanggung_jawab'          => 'nullable',

            ],
            [
                'lokasi_barang'  => 'Lokasi Barang wajib diisi.',
                'tempat_lokasi'  => 'Tempat Barang wajib diisi.',
                'gedung'         => 'nullable',
                'penanggung_jawab'          => 'nullable',

            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $penempatan->lokasi_barang      = $request->input('lokasi_barang');
        $penempatan->gedung             = $request->input('gedung');
        $penempatan->tempat_lokasi      = $request->input('tempat_lokasi');
        $penempatan->penanggung_jawab   = $request->input('penanggung_jawab');

        // dd($penempatans);

        $penempatan->save();

        // Redirect dengan pesan sukses
        return redirect()->route('penempatans.index')->with('success', 'Data Berhasil Ditambahkan');
    }


    public function destroy($id)
    {
        $user = Penempatan::findOrFail($id);
        $user->delete();

        return redirect()->route('penempatans.index')->with('success', 'User deleted successfully.');
    }
}
