<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingUser\AdminPendukung;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use App\Models\MasterBackend\SettingUser\Pptk;

class SubKategoriRkbuController extends Controller
{

    public function index()
    {
        $sub_kategori_rkbus = SubKategoriRkbu::with([
            'kategori_rkbu',
            'rekening_belanja',
            'admin_pendukung',
            'sub_kategori_rekening'
        ])->get();

        $kategori_rkbus         = KategoriRkbu::all();
        $admin_pendukungs       = AdminPendukung::all();
        $sub_kategori_rekenings = SubKategoriRekening::all();
        $rekening_belanjas      = RekeningBelanja::all();

        return view('master_backend.setting_rkbu.sub_kategori_rkbu.index', compact('sub_kategori_rkbus'), [
            'title'         => 'Halaman Sub Kategori RKBU',
            'title2'        => 'Kategori Sub RKBU',
            'routePrefix'   => 'sub_kategori_rkbus'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori_rkbus             = KategoriRkbu::all();
        $admin_pendukungs           = AdminPendukung::all();
        $sub_kategori_rekenings     = SubKategoriRekening::all();
        $rekening_belanjas          = RekeningBelanja::all();
        $pptk                       = Pptk::all();
        // dd($admin_pendukungs);
        return view('master_backend.setting_rkbu.sub_kategori_rkbu.create', compact('kategori_rkbus', 'pptk', 'admin_pendukungs', 'sub_kategori_rekenings', 'rekening_belanjas'), [
            'title'         => 'Halaman Sub Kategori RKBU',
            'title2'        => 'Kategori Sub RKBU',
            'routePrefix'   => 'sub_kategori_rkbus'
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
                'id_kategori_rkbu'                      => 'required',
                'id_admin_pendukung_ppk'                => 'nullable',
                'id_sub_kategori_rekening'              => 'required',
                'id_kode_rekening_belanja'              => 'nullable',
                'kode_sub_kategori_rkbu'                => 'required',
                'nama_sub_kategori_rkbu'                => 'required',
                'status'                                => '',
            ],
            [
                'id_kategori_rkbu.required'             => 'Id Fungsional harus diisi.',
                'id_admin_pendukung_ppk'                => 'Id Admin harus diisi.',
                'id_sub_kategori_rekening.required'     => 'Id Pejabat harus diisi.',
                'id_kode_rekening_belanja'              => 'Id Pejabat harus diisi.',
                'kode_kategori_rkbu.required'           => 'Nama kategori_rkbu harus diisi.',
                'nama_kategori_rkbu.required'           => 'NIP kategori_rkbu harus diisi.',
                'status'                                => 'Status Harus diisi'
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Simpan data ke dalam database
        $sub_kategori_rkbu = new SubKategoriRkbu();

        $sub_kategori_rkbu->fill([
            'id_kategori_rkbu'                  => $request->input('id_kategori_rkbu'),
            'id_pptk'                           => $request->input('id_pptk'),
            'id_admin_pendukung_ppk'            => $request->input('id_admin_pendukung_ppk'),
            'id_sub_kategori_rekening'          => $request->input('id_sub_kategori_rekening'),
            'id_kode_rekening_belanja'          => $request->input('id_kode_rekening_belanja'),
            'kode_sub_kategori_rkbu'            => $request->input('kode_sub_kategori_rkbu'),
            'nama_sub_kategori_rkbu'            => $request->input('nama_sub_kategori_rkbu'),
            'status'                            => $request->input('status'),

        ]);

        // dd($sub_kategori_rkbu);
        $sub_kategori_rkbu->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('sub_kategori_rkbus.index')->with('success', 'kategori_rkbu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKategoriRkbu $subKategoriRkbu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $subKategoriRkbu            = SubKategoriRkbu::all();
        $sub_kategori_rkbus         = SubKategoriRkbu::with('kategori_rkbu')->findOrFail($id);
        $kategori_rkbus             = KategoriRkbu::all();
        $admin_pendukungs           = AdminPendukung::all();
        $sub_kategori_rekenings     = SubKategoriRekening::all();
        $rekening_belanjas          = RekeningBelanja::all();
        $pptk                       = Pptk::all();
        return view('master_backend.setting_rkbu.sub_kategori_rkbu.edit', compact('sub_kategori_rkbus', 'pptk', 'sub_kategori_rekenings', 'kategori_rkbus', 'admin_pendukungs', 'rekening_belanjas'), [
            'title'         => 'Halaman Sub Kategori RKBU',
            'title2'        => 'Kategori Sub RKBU',
            'routePrefix'   => 'sub_kategori_rkbus'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKategoriRkbu $subKategoriRkbu)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_kategori_rkbu'                      => 'required',
                'id_admin_pendukung_ppk'                => 'nullable',
                'id_sub_kategori_rekening'              => 'required',
                'id_kode_rekening_belanja'              => 'required',
                'kode_sub_kategori_rkbu'                => 'required',
                'nama_sub_kategori_rkbu'                => 'required',
                'status'                                => '',
                'id_pptk'                               => 'nullable',
            ],
            [
                'id_kategori_rkbu.required'             => 'Id Fungsional harus diisi.',
                'id_admin_pendukung_ppk'                => 'Id Admin harus diisi.',
                'id_sub_kategori_rekening.required'     => 'Id Sub Kategori Rekening harus diisi.',
                'id_kode_rekening_belanja.required'     => 'Id Kode Rekening Belanja harus diisi.',
                'kode_kategori_rkbu.required'           => 'Nama kategori_rkbu harus diisi.',
                'nama_kategori_rkbu.required'           => 'NIP kategori_rkbu harus diisi.',
                'status'                                => 'Status Harus diisi',
                'id_pptk'                               => 'PPTK Harus diisi',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Simpan data ke dalam database
        $subKategoriRkbu->fill([
            'id_kategori_rkbu'                  => $request->input('id_kategori_rkbu'),
            'id_pptk'                           => $request->input('id_pptk'),
            'kode_sub_kategori_rkbu'            => $request->input('kode_sub_kategori_rkbu'),
            'nama_sub_kategori_rkbu'            => $request->input('nama_sub_kategori_rkbu'),
            'id_admin_pendukung_ppk'            => $request->input('id_admin_pendukung_ppk'),
            'id_sub_kategori_rekening'          => $request->input('id_sub_kategori_rekening'),
            'id_kode_rekening_belanja'          => $request->input('id_kode_rekening_belanja'),
            'status'                            => $request->input('status'),

        ]);

        // dd($request);

        $subKategoriRkbu->update();
        return redirect()->route('sub_kategori_rkbus.index')->with('success', 'kategori_rkbu Delete Success');
    }

    public function destroy(SubKategoriRkbu $subKategoriRkbu)
    {
        $subKategoriRkbu->delete();

        return redirect()->route('sub_kategori_rkbus.index')
            ->with('success', 'kategori_rkbu deleted successfully.');
    }
}
