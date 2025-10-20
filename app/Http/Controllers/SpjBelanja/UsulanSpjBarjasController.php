<?php

namespace App\Http\Controllers\SpjBelanja;

use data;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingBelanja\Spj;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class UsulanSpjBarjasController extends Controller
{

    public function index($id_usulan_barang = null)
    {
        // dd(session()->all());
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $inputIdUsulanBarang = request()->input('id_usulan_barang'); // Ambil input id_usulan_barang dari request
        $id_sub_kategori_rkbu = request()->input('id_sub_kategori_rkbu');

        // Ambil semua detail barang yang sudah masuk SPJ
        $usedDetailIds = SpjDetail::pluck('id_usulan_barang_detail')->toArray();

        // Ambil usulan barang yang pending
        $usulan_barangs = UsulanBarang::with(['sub_kategori_rkbu', 'usulanBarangDetails' => function ($query) use ($usedDetailIds) {
            $query->whereNotIn('id_usulan_barang_detail', $usedDetailIds);
        }])
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('usulan_barangs.tahun_anggaran', $tahunAnggaran)
            ->where('usulan_barangs.status_usulan_barang', 'Selesai')
            ->where('usulan_barangs.status_validasi_direktur', 'Disetujui Direktur')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select(
                'usulan_barangs.id_usulan_barang',
                'usulan_barangs.no_usulan_barang',
                'sub_kategori_rkbus.id_sub_kategori_rkbu',
                'sub_kategori_rkbus.kode_sub_kategori_rkbu as kode_sub',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu as nama_sub'
            )
            ->groupBy(
                'usulan_barangs.id_usulan_barang',
                'usulan_barangs.no_usulan_barang',
                'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'sub_kategori_rkbus.id_sub_kategori_rkbu',
                'sub_kategori_rkbus.id_kategori_rkbu',
                'jenis_kategori_rkbus.id_jenis_kategori_rkbu',
                'sub_kategori_rkbus.id_admin_pendukung_ppk',
                'sub_kategori_rkbus.id_sub_kategori_rekening',
                'sub_kategori_rkbus.id_kode_rekening_belanja',
                'sub_kategori_rkbus.id_sub_kategori_rkbu'
            )
            ->get()
            ->filter(function ($usulan) {
                return $usulan->usulanBarangDetails->count() > 0; // Hanya usulan yang masih punya detail tersisa
            });

        $id_usulan = Spj::join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereNotIn('status_spj', ['Proses Pengadaan Barang', 'Sudah di Bayar'])
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('usulan_barangs.*', 'spjs.*') // Pastikan memilih kolom yang cukup
            //->distinct()
            ->get();

        $sub_kategori_rkbus = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->distinct() // Menghapus duplikasi berdasarkan sub_kategori_rkbus yang sama
            ->get();

        $data_usulan_barangs = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('status_usulan_barang', 'Selesai')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('usulan_barangs.id_usulan_barang') // Pilih hanya id_usulan_barang
            ->distinct()
            ->get();

        // Ambil semua barang sesuai dengan id_usulan_barang
        $get_barangs = [];

        // Iterasi setiap id_usulan_barang untuk mengambil data terkait
        foreach ($id_usulan as $usulan) {
            // Ambil data dari usulan_barang_details
            $barangDetails = UsulanBarangDetail::where('id_usulan_barang', $usulan->id_usulan_barang)
                ->whereNotIn('id_usulan_barang_detail', function ($query) {
                    $query->select('id_usulan_barang_detail')->from('spj_details');
                })
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'rkbus.id_sub_kategori_rkbu')
                ->select(
                    'usulan_barang_details.*',
                    'rkbus.nama_barang',
                    'rkbus.spek',
                    'rkbus.harga_satuan',
                    'rkbus.sisa_vol_rkbu',
                    'rkbus.total_anggaran',
                    'rkbus.sisa_anggaran_rkbu',
                    'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                    'sub_kategori_rkbus.nama_sub_kategori_rkbu'
                )
                ->get();

            // Jika tidak ada data, set sebagai collection kosong
            $get_barangs[$usulan->id_usulan_barang] = $barangDetails->isNotEmpty() ? $barangDetails : collect([]);

            // dd($barangDetails);
        }

        return view('usulan_spj.index', compact('usulan_barangs', 'get_barangs', 'data_usulan_barangs', 'id_usulan', 'sub_kategori_rkbus'), ['title' => 'Halaman SPJ', 'routePrefix' => 'usulan_spj_barjass']);
    }

    public function create() {}

    public function add(Request $request)
    {
        // Validasi data
        $validator = Validator::make(
            $request->all(),
            [
                'id_usulan_barang' => 'required|exists:usulan_barangs,id_usulan_barang',
                'status_spj'       => 'required|string',
                'id_user'          => 'required|exists:users,id_user',
            ],
            [
                'id_usulan_barang.required' => 'ID Usulan Barang harus diisi.',
                'id_usulan_barang.exists'   => 'ID Usulan Barang tidak valid.',
                'status_spj.required'       => 'Status SPJ harus diisi.',
                'id_user.required'          => 'ID User harus diisi.',
                'id_user.exists'            => 'ID User tidak valid.',
            ]
        );

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda: ' . implode(' ', $validator->errors()->all()));
        }

        // Ambil data no_usulan_barang dari relasi
        $usulan_barang = UsulanBarang::find($request->id_usulan_barang);


        if (!$usulan_barang) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Usulan Barang tidak ditemukan.');
        }

        // Buat instance baru untuk SPJ
        $spj = new Spj();
        $spj->no_usulan_barang   = $usulan_barang->no_usulan_barang;
        $spj->status_spj         = $request->status_spj;
        $spj->id_usulan_barang   = $request->id_usulan_barang;
        $spj->id_user            = $request->id_user;

        // Simpan ke database
        $spj->save();

        // Redirect dengan pesan sukses
        return redirect()->route('usulan_spj_barjass.index')->with('success', 'SPJ berhasil disimpan!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_spj' => 'required|uuid',
            'id_usulan_barang' => 'required|uuid',
            'id_usulan_barang_detail' => 'required|array',
            'id_usulan_barang_detail.*' => 'required|uuid',
        ]);

        foreach ($request->id_usulan_barang_detail as $id_usulan_barang_detail) {
            $detail = UsulanBarangDetail::with('subKategoriRkbu')->find($id_usulan_barang_detail);

            if (!$detail) {
                continue;
            }

            $exists = DB::table('spj_details')
                ->where('id_usulan_barang_detail', $id_usulan_barang_detail)
                ->exists();

            if (!$exists) {
                SpjDetail::create([
                    'id_spj' => $request->id_spj,
                    'id_rkbu' => $detail->id_rkbu,
                    'id_usulan_barang' => $detail->id_usulan_barang,
                    'id_usulan_barang_detail' => $id_usulan_barang_detail,
                ]);
            }

            // Pastikan update status benar-benar dijalankan
            UsulanBarangDetail::where('id_usulan_barang_detail', $id_usulan_barang_detail)
                ->update(['status_usulan_barang_detail' => 'Proses Pesanan']);
        }

        return redirect()->route('usulan_spj_barjass.index')
            ->with('success', 'Data berhasil ditransfer ke SPJ dan status item diperbarui.');
    }

    public function show($id_spj)
    {

        // dd($id_spj);
        $invoice = Spj::where('id_spj', $id_spj)->first();
        // dd($invoice->toSql(), $invoice->getBindings());
        $tahunAnggaran = Session::get('tahun_anggaran');
        $judul_headers = JudulHeader::first();

        // dd($invoice);

        // Cek apakah invoice ditemukan (null atau tidak)
        if (!$invoice || $invoice->spjDetail->isEmpty()) {
            return redirect()->back()->with('error', 'Data belum ada. Harap Transfer Data [+Barang] terlebih dahulu!');
        }

        // Pastikan variabel judul_headers ada
        $judul_header1          = $judul_headers->header1 ?? 'Judul Tidak Ada';
        $nama                   = $judul_headers->nama_rs ?? 'Nama Tidak Ada';
        $alamat                 = $judul_headers->alamat_rs ?? 'Alamat Tidak Ada';
        $tlp                    = $judul_headers->tlp_rs ?? 'Tlp Tidak Ada';
        $email                  = $judul_headers->email_rs ?? 'Email Tidak Ada';
        $website                = $judul_headers->header3 ?? 'Website Tidak Ada';
        $gambar1                = $judul_headers->gambar1 ?? 'Website Tidak Ada';
        $gambar2                = $judul_headers->gambar2 ?? 'Website Tidak Ada';


        //Ambil total anggaran usulan barang
        $sum_total_anggaran_usulan_barang = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
            ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
            ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
            ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
            ->sum('usulan_barang_details.total_anggaran_usulan_barang');


        $sum_total_ppn = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
            ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
            ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
            ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
            ->sum('usulan_barang_details.total_ppn');


        $invo = SpjDetail::join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rekenings.id_sub_kategori_rekening', '=', 'sub_kategori_rkbus.id_sub_kategori_rekening')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans', 'kegiatans.id_kegiatan', '=', 'sub_kegiatans.id_kegiatan')
            ->join('programs', 'programs.id_program', '=', 'kegiatans.id_program')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('usulan_barang_details', 'usulan_barangs.id_usulan_barang', '=', 'usulan_barang_details.id_usulan_barang')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('spj_details.id_spj', $invoice->id_spj) // Filter by id_usulan_barang
            ->select(
                'spj_details.*',
                'rkbus.*',
                'programs.*',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'usulan_barangs.no_usulan_barang',
                'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                'kategori_rkbus.kode_kategori_rkbu',
                'kategori_rkbus.nama_kategori_rkbu',
                'rekening_belanjas.*',
                'sub_kegiatans.*',
                'kegiatans.*',
                'aktivitas.*',
                'users.nama_lengkap as nama_pengusul_barang',
                'units.nama_unit as unit',
                'sumber_danas.id_sumber_dana', // Retrieving id_sumber_dana
                'sumber_danas.nama_sumber_dana', // Retrieving sumber_danas field
            ) // Pilih hanya id_usulan_barang
            ->distinct()
            ->first();


        // dd($invo);

        if ($invoice) {
            $keranjang = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
                ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
                ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
                ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
                ->select(
                    'spj_details.*',
                    'rkbus.nama_barang',
                    'usulan_barang_details.*'
                ) // Select kolom yang dibutuhkan
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }


        // dd($invoice);

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');

        $data = [
            'no_inv'            => $invo,
            'invoice'           => $invoice,
            'keranjang'         => $keranjang,
            'get_total'         => $sum_total_anggaran_usulan_barang,
            'ppn'               => $sum_total_ppn,
            'judul_header1'     => $judul_header1,
            'nama'              => $nama,
            'alamat'            => $alamat,
            'tlp'               => $tlp,
            'email'             => $email,
            'website'           => $website,
            'gambar1'           => $gambar1,
            'gambar2'           => $gambar2,
            'routePrefix'       => 'usulan_spj_barjass'
        ];

        // dd($data);


        return view('usulan_spj.invoice', $data);
    }

    public function edit($id)
    {
        $usulan_barang_details      = UsulanBarangDetail::with('usulan_barang')->findOrFail($id);
        $usulan_barangs             = UsulanBarang::all();
        $rkbu                       = Rkbu::where('id_rkbu', $usulan_barang_details->id_rkbu)->first();
        $uraian_satu                = UraianSatu::all(); // Ambil uraian1
        $uraian_dua                 = UraianDua::all(); // Ambil uraian2
        $tahunAnggaran              = Session::get('tahun_anggaran');

        $invo = SpjDetail::join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rekenings.id_sub_kategori_rekening', '=', 'sub_kategori_rkbus.id_sub_kategori_rekening')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans', 'kegiatans.id_kegiatan', '=', 'sub_kegiatans.id_kegiatan')
            ->join('programs', 'programs.id_program', '=', 'kegiatans.id_program')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('usulan_barang_details', 'usulan_barangs.id_usulan_barang', '=', 'usulan_barang_details.id_usulan_barang')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            //->where('spj_details.id_usulan_barang', $invoice->id_usulan_barang) // Filter by id_usulan_barang
            ->select(
                'spj_details.*',
                'rkbus.*',
                'programs.*',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                'kategori_rkbus.kode_kategori_rkbu',
                'kategori_rkbus.nama_kategori_rkbu',
                'rekening_belanjas.*',
                'sub_kegiatans.*',
                'programs.*',
                'kegiatans.*',
                'aktivitas.*',
                'users.nama_lengkap as nama_pengusul_barang',
                'units.nama_unit as unit',
                'sumber_danas.id_sumber_dana', // Retrieving id_sumber_dana
                'sumber_danas.nama_sumber_dana', // Retrieving sumber_danas field
            ) // Pilih hanya id_usulan_barang
            ->distinct()
            ->first();


        // Ambil id_ksp, id_user, dan tahun_anggaran dari session
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = session('tahun_anggaran'); // Lebih konsisten menggunakan session helper
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi RKA

        // Kirim data ke view
        return view('usulan_spj.edit', compact('usulan_barang_details', 'rkbu', 'uraian_satu', 'uraian_dua', 'invo'), ['title' => 'Halaman SPJ', 'routePrefix' => 'usulan_spj_barjass']);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_barang'           => 'required|string|max:255',
            'vol_1_detail'          => 'required|numeric',
            'satuan_1_detail'       => 'required|string',
            'vol_2_detail'          => 'nullable|numeric',
            'satuan_2_detail'       => 'nullable|string',
            'spek_detail'           => 'required|string',
            'harga_barang'          => 'required|numeric',
            'jumlah_usulan_barang'  => 'required|numeric',
            'ppn'                   => 'required|numeric',
            'total_anggaran_usulan_barang' => 'required|numeric',
            // 'link_ekatalog' => 'nullable|string',
            // 'id_sub_kategori_rkbu' => 'required|uuid',
        ]);

        // Ambil detail usulan barang berdasarkan id
        $usulanBarangDetail = UsulanBarangDetail::findOrFail($id);


        // Ambil data rkbu terkait
        $rkbu = Rkbu::findOrFail($usulanBarangDetail->id_rkbu);

        // Perhitungan total usulan anggaran barang (sebelum update)
        $totalUsulanAnggaranBarangLain = UsulanBarangDetail::where('id_rkbu', $usulanBarangDetail->id_rkbu)
            ->where('id_usulan_barang_detail', '!=', $id) // Kecualikan barang yang sedang di-update
            ->sum('total_anggaran_usulan_barang');

        // Ambil data dari request
        $harga_barang           = $request->input('harga_barang');
        $jumlah_usulan_barang   = $request->input('jumlah_usulan_barang');
        $ppn                    = $request->input('ppn');
        $sum_total_ppn          = ($ppn / 100) * $jumlah_usulan_barang * $harga_barang;

        // Hitung total anggaran barang yang sedang di-update
        $totalAnggaranBarang = ($harga_barang * $jumlah_usulan_barang) + (($harga_barang * $jumlah_usulan_barang) * ($ppn / 100));

        // Total anggaran setelah update
        $totalAnggaranSetelahUpdate = $totalUsulanAnggaranBarangLain + $totalAnggaranBarang;

        // Cek apakah total anggaran setelah update tidak melebihi total anggaran yang diperbolehkan di rkbu
        if ($totalAnggaranSetelahUpdate <= $rkbu->total_anggaran) {
            // Update detail usulan barang
            $usulanBarangDetail->vol_1_detail = $request->input('vol_1_detail');
            $usulanBarangDetail->satuan_1_detail = $request->input('satuan_1_detail');
            $usulanBarangDetail->vol_2_detail = $request->input('vol_2_detail');
            $usulanBarangDetail->satuan_2_detail = $request->input('satuan_2_detail');
            $usulanBarangDetail->spek_detail = $request->input('spek_detail');
            $usulanBarangDetail->harga_barang = $harga_barang;
            $usulanBarangDetail->jumlah_usulan_barang = $jumlah_usulan_barang;
            $usulanBarangDetail->ppn = $ppn;
            $usulanBarangDetail->total_ppn = $sum_total_ppn;
            $usulanBarangDetail->total_anggaran_usulan_barang = $totalAnggaranBarang;
            $usulanBarangDetail->save();

            // Update sisa anggaran dan sisa volume di tabel rkbu
            $rkbu->sisa_vol_rkbu = $rkbu->jumlah_vol - UsulanBarangDetail::where('id_rkbu', $rkbu->id_rkbu)->sum('jumlah_usulan_barang');
            $rkbu->sisa_anggaran_rkbu = $rkbu->total_anggaran - UsulanBarangDetail::where('id_rkbu', $rkbu->id_rkbu)->sum('total_anggaran_usulan_barang');
            $rkbu->save();

            return redirect()->route('usulan_spj_barjass.index', $usulanBarangDetail->id_usulan_barang)
                ->with('success', 'Detail usulan barang berhasil diperbarui.');
        } else {
            // Jika total anggaran setelah update melebihi, tampilkan alert
            return redirect()->back()->with('lebih', 'Total Usulan melebihi Pagu Anggaran.');
        }
    }

    public function delete($id_spj_detail)
    {
        try {
            $deleted = SpjDetail::where('id_spj_detail', $id_spj_detail)->delete();

            if ($deleted === 0) {
                return redirect()->back()->with('error', 'Data SPJ tidak ditemukan atau sudah dihapus.');
            }

            return redirect()->route('usulan_spj_barjass.index')->with('success', 'Data SPJ berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id_spj)
    {
        try {
            // Validasi input
            $request->validate([
                'id_usulan_barang'              => 'required|exists:usulan_barangs,id_usulan_barang',
                'status_validasi_pengadaan'     => 'required|string',
                'keterangan_validasi_pengadaan' => 'nullable|string', // Validasi sebagai string
            ]);

            // Perbarui hanya kolom tertentu jika status_spj adalah 'Pending'
            if ($request->status_validasi_pengadaan === 'Pending') {
                UsulanBarang::where('id_usulan_barang', $request->id_usulan_barang)
                    ->update([
                        'keterangan_validasi_pengadaan' => $request->keterangan_validasi_pengadaan ?? '', // Default jika null
                        'tanggal_validasi_pengadaan'    => now()->toDateString(),
                    ]);

                // Update hanya status_spj pada tabel spjs
                $spj = Spj::findOrFail($id_spj);
                $spj->status_spj = 'Pending Pengadaan';
                $spj->save();

                // ✅ Ambil id_usulan_barang_detail dari spj_details untuk spj ini
                $idDetails = DB::table('spj_details')
                    ->where('id_spj', $id_spj)
                    ->pluck('id_usulan_barang_detail')
                    ->toArray();

                // ✅ Update hanya detail yang muncul di spj_details
                if (!empty($idDetails)) {
                    UsulanBarangDetail::whereIn('id_usulan_barang_detail', $idDetails)
                        ->update(['status_usulan_barang_detail' => 'Pending Pengadaan Barang']);
                }
            } else {
                // Jika status_spj bukan 'Pending', jalankan proses lengkap
                $usulanBarang = UsulanBarang::find($request->id_usulan_barang);
                if (!$usulanBarang) {
                    return response()->json(['error' => 'Data tidak ditemukan'], 404);
                }

                // Update tabel usulan_barangs
                $usulanBarang->update([
                    'status_validasi_pengadaan'     => $request->status_validasi_pengadaan,
                    'keterangan_validasi_pengadaan' => $request->keterangan_validasi_pengadaan ?? '', // Default jika null
                    'tanggal_validasi_pengadaan'    => now()->toDateString(),
                ]);

                // Update tabel spjs
                $spj                = Spj::findOrFail($id_spj);
                $spj->status_spj    = 'Proses Pengadaan Barang';
                $spj->save();

                // ✅ Ambil id_usulan_barang_detail dari spj_details untuk spj ini
                $idDetails = DB::table('spj_details')
                    ->where('id_spj', $id_spj)
                    ->pluck('id_usulan_barang_detail')
                    ->toArray();

                // ✅ Update hanya detail yang muncul di spj_details
                if (!empty($idDetails)) {
                    UsulanBarangDetail::whereIn('id_usulan_barang_detail', $idDetails)
                        ->update(['status_usulan_barang_detail' => 'Proses Pengadaan Barang']);
                }

                // Insert ke tabel master_spj
                $tahunAnggaran = session('tahun_anggaran');
                DB::table('master_spjs')->insert([
                    'id_master_spj'     => (string) Str::uuid(),
                    'id_spj'            => $id_spj,
                    'tahun_anggaran'    => $tahunAnggaran,
                    'id_user'           => auth()->user()->id_user,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            return redirect()->route('usulan_spj_barjass.index')
                ->with('success', 'Data berhasil diperbarui dan disimpan.');
        } catch (\Exception $e) {
            // Tangani error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id_spj_detail)
    {
        try {
            // Cari data spj_detail berdasarkan id_spj_detail
            $detail = SpjDetail::findOrFail($id_spj_detail);

            // Hapus data
            $detail->delete();

            return redirect()
                ->route('usulan_spj_barjass.index')
                ->with('success', 'Data detail berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus detail: ' . $e->getMessage());
        }
    }
}
