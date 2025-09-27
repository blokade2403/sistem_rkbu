<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingUser\PpkKeuangan;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\PejabatPengadaan;

class PejabatPengadaanController extends Controller
{

    public function index()
    {
        $pejabat_pengadaans = PejabatPengadaan::all();
        $tahun_anggaran     = TahunAnggaran::all();
        return view(
            'master_backend.setting_users.pejabat_pengadaan.index',
            [
                'pejabat_pengadaans'        => $pejabat_pengadaans,
                'tahun_anggaran'            => $tahun_anggaran,
                'title'                     => 'Pejabat Pengadaan'
            ]
        );
    }

    public function create()
    {
        $tahun_anggaran = TahunAnggaran::all();
        $ppk_keuangan   = PpkKeuangan::all();
        return view(
            'master_backend.setting_users.pejabat_pengadaan.create',
            [
                'title'             => 'Pejabat Pengadaan',
                'tahun_anggaran'    => $tahun_anggaran,
                'ppk_keuangan'      => $ppk_keuangan,
            ]
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ppk'                 => 'required',
                'nip_ppk'                  => 'required',
                'nama_ppbj'                 => 'required',
                'nip_ppbj'                  => 'required',
                'nama_pengurus_barang'     => 'required',
                'nama_direktur'            => 'required',
                'nama_bendahara'           => 'required',
                'nip_bendahara'            => 'required',
                'nip_direktur'             => 'required',
                'nip_pengurus_barang'      => 'required',
                'id_tahun_anggaran'        => 'required',
                'id_ppk_keuangan'          => 'required',
                'status'                   => 'required',
            ],
            [
                'nama_ppk.required'             => 'Nama PPK harus terisi',
                'nip_ppk.required'              => 'NIP PPK harus terisi',
                'nama_ppbj.required'             => 'Nama PBJ harus terisi',
                'nip_ppbj.required'              => 'NIP PBJ harus terisi',
                'nama_pengurus_barang.required' => 'Nama Pengurus Barang harus terisi',
                'nama_direktur.required'        => 'Nama Direktur harus terisi',
                'nama_bendahara.required'       => 'Nama Bendahara harus terisi',
                'nip_bendahara.required'        => 'NIP Bendahara harus terisi',
                'nip_direktur.required'         => 'NIP Direktur harus terisi',
                'nip_pengurus_barang.required'  => 'NIP Pengurus Barang harus terisi',
                'id_tahun_anggaran.required'    => 'Tahun Anggaran harus terisi',
                'id_ppk_keuangan.required'      => 'PPK Keuangan harus terisi',
                'status.required'               => 'Status harus terisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $pejabat_pengadaans  = new PejabatPengadaan(
            [
                'nama_ppk'                 => $request->nama_ppk,
                'nip_ppk'                  => $request->nip_ppk,
                'nama_pbj'                 => $request->nama_pbj,
                'nip_pbj'                  => $request->nip_pbj,
                'nama_pengurus_barang'     => $request->nama_pengurus_barang,
                'nama_direktur'            => $request->nama_direktur,
                'nama_bendahara'           => $request->nama_bendahara,
                'nip_bendahara'            => $request->nip_bendahara,
                'nip_direktur'             => $request->nip_direktur,
                'nip_pengurus_barang'      => $request->nip_pengurus_barang,
                'id_tahun_anggaran'        => $request->id_tahun_anggaran,
                'id_ppk_keuangan'          => $request->id_ppk_keuangan,
                'status'                   => $request->status,
            ]
        );

        $pejabat_pengadaans->save();
        return redirect()->route('pejabat_pengadaans.index')->with('success', 'Data Berhasil Di Tambah');
    }

    public function show(PejabatPengadaan $PejabatPengadaan)
    {
        //
    }

    public function edit(PejabatPengadaan $PejabatPengadaan)
    {
        $tahun_anggaran = TahunAnggaran::all();
        $ppk_keuangan   = PpkKeuangan::all();
        return view(
            'master_backend.setting_users.pejabat_pengadaan.edit',
            [
                'title'             => 'Pejabat Pengadaan',
                'tahun_anggaran'    => $tahun_anggaran,
                'ppk_keuangan'      => $ppk_keuangan,
                'pejabat_pengadaan' => $PejabatPengadaan,
            ]
        );
    }

    public function update(Request $request, PejabatPengadaan $PejabatPengadaan)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'nama_ppk'                 => 'required',
                'nip_ppk'                  => 'required',
                'nama_ppbj'                 => 'required',
                'nip_ppbj'                  => 'required',
                'nama_pengurus_barang'     => 'required',
                'nama_direktur'            => 'required',
                'nama_bendahara'           => 'required',
                'nip_bendahara'            => 'required',
                'nip_direktur'             => 'required',
                'nip_pengurus_barang'      => 'required',
                'id_tahun_anggaran'        => 'required',
                'id_ppk_keuangan'          => 'required',
                'status'                   => 'required',
            ],
            [
                'nama_ppk.required'             => 'Nama PPK harus terisi',
                'nip_ppk.required'              => 'NIP PPK harus terisi',
                'nama_ppbj.required'             => 'Nama PBJ harus terisi',
                'nip_ppbj.required'              => 'NIP PBJ harus terisi',
                'nama_pengurus_barang.required' => 'Nama Pengurus Barang harus terisi',
                'nama_direktur.required'        => 'Nama Direktur harus terisi',
                'nama_bendahara.required'       => 'Nama Bendahara harus terisi',
                'nip_bendahara.required'        => 'NIP Bendahara harus terisi',
                'nip_direktur.required'         => 'NIP Direktur harus terisi',
                'nip_pengurus_barang.required'  => 'NIP Pengurus Barang harus terisi',
                'id_tahun_anggaran.required'    => 'Tahun Anggaran harus terisi',
                'id_ppk_keuangan.required'      => 'PPK Keuangan harus terisi',
                'status.required'               => 'Status harus terisi',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages  = implode(' ', $errors->all());

            return redirect()->back()->withInput()->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $PejabatPengadaan->update($request->all());
        return redirect()->route('pejabat_pengadaans.index')->with('success', 'Data Berhasil Di Ubah');
    }

    public function destroy($id)
    {

        $PejabatPengadaan = PejabatPengadaan::findOrFail($id);
        $PejabatPengadaan->delete();

        return redirect()->route('pejabat_pengadaans.index')->with('success', 'Data Pejabat Pengadaans Berhasil dihapus.');
    }
}
