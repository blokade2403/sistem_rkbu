<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\Pptk;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;

class PptkController extends Controller
{

    public function index()
    {
        $pptks = Pptk::all();
        $tahun_anggaran = TahunAnggaran::all();
        return view(
            'master_backend.setting_users.pptk.index',
            [
                'pptks'             => $pptks,
                'tahun_anggaran'    => $tahun_anggaran,
                'title'             => 'Halaman PPTK',
                'title2'            => 'PPTK',
                'routePrefix'       => 'pptks'
            ]
        );
    }

    public function create()
    {
        $tahun_anggaran = TahunAnggaran::all();
        return view('master_backend.setting_users.pptk.create',  [
            'title'             => 'Halaman PPTK',
            'title2'            => 'PPTK',
            'routePrefix'       => 'pptks',
            'tahun_anggaran'    => $tahun_anggaran
        ]);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'nama_pptk'             => 'required',
                'nip_pptk'              => 'required',
                'id_tahun_anggaran'     => 'required',
                'status_pptk'           => 'required',
            ],
            [
                'nama_pptk'             => 'Nama Harus Terisi',
                'nip_pptk'              => 'NIP Harus Terisi',
                'id_tahun_anggaran'     => 'Tahun Anggaran Harus Terisi',
                'status_pptk'           => 'status_pptk Harus Terisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $pptks  = new Pptk(
            [
                'nama_pptk'             => $request->nama_pptk,
                'nip_pptk'              => $request->nip_pptk,
                'id_tahun_anggaran'     => $request->id_tahun_anggaran,
                'id_jabatan'            => $request->id_jabatan,
                'status_pptk'           => $request->status_pptk,
            ]
        );
        $pptks->save();
        return redirect()->route('pptks.index')->with('success', 'Data Berhasil Di Tambah');
    }

    public function show(Pptk $Pptk)
    {
        //
    }

    public function edit(Pptk $Pptk)
    {
        $tahun_anggaran = TahunAnggaran::all();
        return view('master_backend.setting_users.pptk.edit',  [
            'pptks'             => $Pptk,
            'tahun_anggaran'    => $tahun_anggaran,
            'title'             => 'Halaman PPTK',
            'title2'            => 'PPTK',
            'routePrefix'       => 'pptks'
        ]);
    }

    public function update(Request $request, Pptk $Pptk)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'nama_pptk'             => 'required',
                'nip_pptk'              => 'required',
                'id_tahun_anggaran'     => 'required',
                'status_pptk'           => 'required',
            ],
            [
                'nama_pptk'             => 'Nama Harus Terisi',
                'nip_pptk'              => 'NIP Harus Terisi',
                'id_tahun_anggaran'     => 'Tahun Anggaran Harus Terisi',
                'status_pptk'           => 'status_pptk Harus Terisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $Pptk->update($request->all());
        return redirect()->route('pptks.index')->with('success', 'Data Berhasil Di Ubah');
    }

    public function destroy($id)
    {

        $fase = Pptk::findOrFail($id);
        $fase->delete();

        return redirect()->route('pptks.index')->with('success', 'Data fase Berhasil dihapus.');
    }
}
