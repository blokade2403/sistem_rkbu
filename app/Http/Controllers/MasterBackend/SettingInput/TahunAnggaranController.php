<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\Fase;

class TahunAnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun_anggarans = TahunAnggaran::all();
        return view('master_backend.setting_input.tahun_anggaran.index', compact('tahun_anggarans'), [
            'title'       => 'Halaman Tahun Anggaran',
            'title2'      => 'Tahun Anggaran',
            'routePrefix' => 'tahun_anggarans'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fase   = Fase::all();
        return view('master_backend.setting_input.tahun_anggaran.create', [
            'title'         => 'Halaman Tahun Anggaran',
            'title2'        => 'Tahun Anggaran',
            'routePrefix'   => 'tahun_anggarans',
            'fase'          => $fase
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TahunAnggaran $TahunAnggaran)
    {
        $request->validate([
            'nama_tahun_anggaran'  => 'required',
            'status'               => 'required',
            'fase_tahun'           => 'required',
        ]);

        $TahunAnggaran = new TahunAnggaran([
            'nama_tahun_anggaran'       => $request->nama_tahun_anggaran,
            'status'                    => $request->status,
            'fase_tahun'                => $request->fase_tahun,

        ]);

        // dd($tahunAnggaran);

        $TahunAnggaran->save();

        return redirect()->route('tahun_anggarans.index')
            ->with('success', 'Tahun Anggaran updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAnggaran $TahunAnggaran)
    {
        return view('master_backend.setting_input.tahun_anggaran.show', compact('TahunAnggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAnggaran $TahunAnggaran)
    {
        $fase   = Fase::all();
        return view('master_backend.setting_input.tahun_anggaran.edit', compact('TahunAnggaran'), [
            'title'         => 'Halaman Tahun Anggaran',
            'title2'        => 'Tahun Anggaran',
            'routePrefix'   => 'tahun_anggarans',
            'fase'          => $fase,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAnggaran $TahunAnggaran)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_tahun_anggaran'  => 'required',
                'status'               => 'required',
                'fase_tahun'           => 'required',
            ],
            [
                'nama_tahun_anggaran'  => 'Nama Tahun Harus diisi !!',
                'status'               => 'Status Harus dipilih !!',
                'fase_tahun'           => 'Fase Tahun Harus dipilih !!',
            ]
        );

        if ($validator->fails()) {
            $errors             = $validator->error();
            $errorMessages      = implode(' ', $errors->all());
            return redirect()->back()->withInput()->with('error', 'Periksa Kembali Update Data Anda !!' . $errorMessages);
        }

        $TahunAnggaran->update($request->all());
        return redirect()->route('tahun_anggarans.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAnggaran $TahunAnggaran)
    {
        $TahunAnggaran->delete();

        return redirect()->route('tahun_anggarans.index')
            ->with('success', 'Tahun Anggaran deleted successfully.');
    }
}
