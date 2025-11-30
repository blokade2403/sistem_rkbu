<?php

namespace App\Http\Controllers\Pengadaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perusahaans    = Perusahaan::all();
        return view('pengadaan.perusahaan.index', compact('perusahaans'), ['title' => 'Halaman Perusahaan', 'routePrefix' => 'perusahaans']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengadaan.perusahaan.create', [
            'title' => 'Halaman Perusahaan',
            'title2' => 'Halaman Perusahaan',
            'routePrefix' => 'perusahaans'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make(
            $request->all(),
            [
                'nama_perusahaan'               => 'required',
                'alamat_perusahaan'             => 'required',
                'email_perusahaan'              => 'required|email',
                'tlp_perusahaan'                => 'required',
                'nama_direktur_perusahaan'      => 'required',
                'jabatan_perusahaan'            => 'required',
                'no_npwp'                       => 'required',
            ],
            [
                'nama_perusahaan.required'          => 'Nama perusahaan harus diisi.',
                'alamat_perusahaan.required'        => 'Alamat perusahaan harus diisi.',
                'email_perusahaan.required'         => 'Email perusahaan harus diisi.',
                'email_perusahaan.email'            => 'Format email tidak valid.',
                'tlp_perusahaan.required'           => 'Nomor telepon perusahaan harus diisi.',
                'tlp_perusahaan.numeric'            => 'Nomor telepon harus berupa angka.',
                'nama_direktur_perusahaan.required' => 'Nama direktur perusahaan harus diisi.',
                'jabatan_perusahaan.required'       => 'Jabatan perusahaan harus diisi.',
                'no_npwp.required'                  => 'Nomor NPWP perusahaan harus diisi.',
            ]
        );

        // Check if validation failed
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Create a new Perusahaan instance (or find one if updating)
        $perusahaan = new Perusahaan();

        // Populate the model with validated data
        $perusahaan->nama_perusahaan                = $request->input('nama_perusahaan');
        $perusahaan->alamat_perusahaan              = $request->input('alamat_perusahaan');
        $perusahaan->email_perusahaan               = $request->input('email_perusahaan');
        $perusahaan->tlp_perusahaan                 = $request->input('tlp_perusahaan');
        $perusahaan->nama_direktur_perusahaan       = $request->input('nama_direktur_perusahaan');
        $perusahaan->jabatan_perusahaan             = $request->input('jabatan_perusahaan');
        $perusahaan->no_npwp                        = $request->input('no_npwp');

        // dd($perusahaan);
        // Save the model to the database
        $perusahaan->save();

        // Redirect with success message
        return redirect()->route('perusahaans.index')->with('success', 'Perusahaan berhasil disimpan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('pengadaan.perusahaan.edit', compact('perusahaan'), [
            'title' => 'Halaman Perusahaan',
            'title2' => 'Halaman Perusahaan',
            'routePrefix' => 'perusahaans'
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validator = Validator::make(
            $request->all(),
            [
                'nama_perusahaan'               => 'required',
                'alamat_perusahaan'             => 'required',
                'email_perusahaan'              => 'required|email',
                'tlp_perusahaan'                => 'required',
                'nama_direktur_perusahaan'      => 'required',
                'jabatan_perusahaan'            => 'required',
                'no_npwp'                       => 'required',
            ],
            [
                'nama_perusahaan.required'          => 'Nama perusahaan harus diisi.',
                'alamat_perusahaan.required'        => 'Alamat perusahaan harus diisi.',
                'email_perusahaan.required'         => 'Email perusahaan harus diisi.',
                'email_perusahaan.email'            => 'Format email tidak valid.',
                'tlp_perusahaan.required'           => 'Nomor telepon perusahaan harus diisi.',
                'tlp_perusahaan.numeric'            => 'Nomor telepon harus berupa angka.',
                'nama_direktur_perusahaan.required' => 'Nama direktur perusahaan harus diisi.',
                'jabatan_perusahaan.required'       => 'Jabatan perusahaan harus diisi.',
                'no_npwp.required'                  => 'Nomor NPWP perusahaan harus diisi.',
            ]
        );

        // Check if validation failed
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Create a new Perusahaan instance (or find one if updating)
        $perusahaan = Perusahaan::find($id);

        // Populate the model with validated data
        $perusahaan->nama_perusahaan                = $request->input('nama_perusahaan');
        $perusahaan->alamat_perusahaan              = $request->input('alamat_perusahaan');
        $perusahaan->email_perusahaan               = $request->input('email_perusahaan');
        $perusahaan->tlp_perusahaan                 = $request->input('tlp_perusahaan');
        $perusahaan->nama_direktur_perusahaan       = $request->input('nama_direktur_perusahaan');
        $perusahaan->jabatan_perusahaan             = $request->input('jabatan_perusahaan');
        $perusahaan->no_npwp                        = $request->input('no_npwp');

        // dd($perusahaan);
        // Save the model to the database
        $perusahaan->save();

        // Redirect with success message
        return redirect()->route('perusahaans.index')->with('success', 'Perusahaan berhasil disimpan!');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();
        return redirect()->route('perusahaans.index')->with('success', 'Data Berhasil di Hapus');
    }
}
