<?php

namespace App\Http\Controllers\MasterSpj;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\DataTables\MasterSpj\MasterSpjDataTable;
use App\Models\MasterBackend\SettingBelanja\Spj;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingBelanja\MasterSpj;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;
use App\Models\MasterBackend\SettingUser\AdminPendukung;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class MasterSpjController extends Controller
{
    public function index(Request $request)
    {
        // dd(session()->all());
        $tahunAnggaran = Session::get('tahun_anggaran');
        $judul_headers = JudulHeader::first();
        $idAdminPendukungPpk = session('id_admin_pendukung_ppk');
        $nama_level_user = session('nama_level_user') ?? 'Guest'; // Default 'Guest'

        // Data header
        $judul_header1 = $judul_headers->header1 ?? 'Judul Tidak Ada';
        $nama = $judul_headers->nama_rs ?? 'Nama Tidak Ada';
        $alamat = $judul_headers->alamat_rs ?? 'Alamat Tidak Ada';
        $tlp = $judul_headers->tlp_rs ?? 'Tlp Tidak Ada';
        $email = $judul_headers->email_rs ?? 'Email Tidak Ada';
        $website = $judul_headers->header3 ?? 'Website Tidak Ada';
        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');

        // Default data collections
        $invo = collect();
        $invo_pendukung_ppk = collect();
        $invo_pb = collect();
        $invo_bendahara = collect();
        $invo_direktur = collect();
        $invo_ksp = collect();

        // Level user pengadaan PPK
        $level_pengadaan_ppk = ['Administrator', 'Admin PPK', 'PPTK', 'PPK', 'PPBJ', 'Validasi Keuangan', 'Verifikator', 'PPK Keuangan'];
        $level_pb = ['Pengurus Barang', 'Administrator'];
        $level_pptk = ['PPTK Alkes', 'Administrator'];
        $level_bendahara = ['Bendahara', 'Administrator'];

        $sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('usulan_barangs', 'usulan_barangs.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('spj_details', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('spjs', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('master_spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->select('sub_kategori_rkbus.*', 'master_spjs.id_master_spj') // Tambahkan id_master_spj ke hasil select
            ->distinct() // Hilangkan duplikasi
            ->get();

        $query_sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('usulan_barangs', 'usulan_barangs.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('spj_details', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('spjs', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('master_spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->select('sub_kategori_rkbus.*', 'master_spjs.id_master_spj') // Tambahkan id_master_spj ke hasil select
            ->distinct(); // Hilangkan duplikasi

        $request->validate([
            'sub_kategori_rkbu' => 'nullable|exists:sub_kategori_rkbus,id_sub_kategori_rkbu',
            'status_pembayaran' => 'nullable|in:Sudah di Bayar,Revisi,Bayar Parsial',
        ]);


        // Eksekusi query
        $filteredData = $query_sub_kategori_rkbus->get();

        if (in_array($nama_level_user, $level_pengadaan_ppk)) {
            // Query untuk pengadaan PPK
            $invo = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->when(request('sub_kategori_rkbu'), function ($query_sub_kategori_rkbus) {
                    $query_sub_kategori_rkbus->where('sub_kategori_rkbus.id_sub_kategori_rkbu', request('sub_kategori_rkbu'));
                }) // Tambahkan filter dinamis untuk sub_kategori_rkbu
                ->when(request('status_pembayaran'), function ($query) {
                    $query->where('master_spjs.status_pembayaran', request('status_pembayaran'));
                }) // Tambahkan filter dinamis untuk status_pembayaran
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*', 'sub_kategori_rkbus.*')
                ->get();
        } elseif ($nama_level_user === 'Pendukung PPK') {
            // Query untuk Pendukung PPK
            $invo_pendukung_ppk = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('master_spjs.id_admin_pendukung_ppk', $idAdminPendukungPpk)
                ->where('master_spjs.status_proses_pesanan', 'Selesai')
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();
        }

        $sum_total_spj = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->where('master_spjs.status_proses_pesanan', 'Selesai')
            ->when(request('sub_kategori_rkbu'), function ($query_sub_kategori_rkbus) {
                $query_sub_kategori_rkbus->where('sub_kategori_rkbus.id_sub_kategori_rkbu', request('sub_kategori_rkbu'));
            }) // Tambahkan filter dinamis untuk sub_kategori_rkbu
            ->when(request('status_pembayaran'), function ($query) {
                $query->where('master_spjs.status_pembayaran', request('status_pembayaran'));
            }) // Tambahkan filter dinamis untuk status_pembayaran
            ->distinct()
            ->select('master_spjs.*', 'usulan_barangs.*', 'sub_kategori_rkbus.*')
            ->sum('master_spjs.bruto');

        $sum_total_spj_dibayar = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->where('master_spjs.status_proses_pesanan', 'Selesai')
            ->when(request('sub_kategori_rkbu'), function ($query_sub_kategori_rkbus) {
                $query_sub_kategori_rkbus->where('sub_kategori_rkbus.id_sub_kategori_rkbu', request('sub_kategori_rkbu'));
            }) // Tambahkan filter dinamis untuk sub_kategori_rkbu
            ->when(request('status_pembayaran'), function ($query) {
                $query->where('master_spjs.status_pembayaran', request('status_pembayaran'));
            }) // Tambahkan filter dinamis untuk status_pembayaran
            ->distinct()
            ->select('master_spjs.*', 'usulan_barangs.*', 'sub_kategori_rkbus.*')
            ->sum('master_spjs.pembayaran');

        if ($nama_level_user === 'Bendahara') {
            // Query untuk Pendukung PPK
            $invo_bendahara = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('master_spjs.status_serah_terima_bendahara', 'Selesai')
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();
        }

        // dd($invo_bendahara);
        if ($nama_level_user === 'Direktur') {
            // Query untuk Pendukung PPK
            $invo_direktur = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('master_spjs.status_verifikasi_ppk_keuangan', 'Selesai')
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();
        }

        // Query untuk Pengurus Barang
        if (in_array($nama_level_user, $level_pb)) {
            $invo_pb = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('master_spjs.status_proses_pesanan', 'Selesai')
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();
        }

        // dd($invo_bendahara);
        if ($nama_level_user === 'Validasi') {
            // Query untuk Pendukung PPK
            $invo_ksp = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('usulan_barangs.id_user', session('id_user'))
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();
        }

        // dd(session()->all());

        $tahunAnggaran      = session('tahun_anggaran');
        $idPejabat          = session('id_pejabat');
        $nama_level_user    = session('nama_level_user') ?? 'Guest'; // Default 'Guest'

        session_start(); // Pastikan session dimulai

        // Inisialisasi default untuk menghindari error
        $invo_pptk_umum = [];
        $invo_pptk_alkes = [];
        $invo_pptk_penmed = [];

        // Mapping level user ke ID jabatan
        $levelToJabatan = [
            'PPTK Umum'     => '9cdfc135-d1dc-452f-8953-570df9133468',
            'PPTK Alkes'    => '9d0f9b8b-4326-4501-bf0a-bd2c135b205d',
            'PPTK'          => '9d0f9b9b-75c0-49b4-88a1-7002b441842e',
        ];

        // Periksa apakah session nama_level_user sesuai dan ada dalam mapping
        if (isset($nama_level_user) && array_key_exists($nama_level_user, $levelToJabatan)) {
            $idJabatan = $levelToJabatan[$nama_level_user];

            $queryResult = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
                ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
                ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
                ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('jabatans', 'jabatans.id_jabatan', '=', 'pejabats.id_jabatan') // Join ke tabel jabatans
                ->join('units', 'users.id_unit', '=', 'units.id_unit')
                ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
                ->where('master_spjs.status_proses_pesanan', 'Selesai')
                ->where('jabatans.id_jabatan', $idJabatan) // Kondisi berdasarkan ID jabatan
                ->distinct()
                ->select('master_spjs.*', 'usulan_barangs.*')
                ->get();

            // Simpan hasil query ke variabel sesuai nama_level_user
            if ($nama_level_user == 'PPTK Umum') {
                $invo_pptk_umum = $queryResult;
            } elseif ($nama_level_user == 'PPTK Alkes') {
                $invo_pptk_alkes = $queryResult;
            } elseif ($nama_level_user == 'PPTK') {
                $invo_pptk_penmed = $queryResult;
            }
        }

        // Data untuk view
        $data = [
            'no_inv'                => $invo,
            'invo_pendukung_ppk'    => $invo_pendukung_ppk,
            'no_inv_pb'             => $invo_pb,
            'invo_pptk_umum'        => $invo_pptk_umum,
            'invo_direktur'         => $invo_direktur,
            'invo_pptk_alkes'       => $invo_pptk_alkes,
            'invo_pptk_penmed'      => $invo_pptk_penmed,
            'invo_bendahara'        => $invo_bendahara,
            'invo_ksp'              => $invo_ksp,
            'sum_total_spj'         => $sum_total_spj,
            'sum_total_spj_dibayar' => $sum_total_spj_dibayar,
            'judul_header1'         => $judul_header1,
            'nama'                  => $nama,
            'alamat'                => $alamat,
            'tlp'                   => $tlp,
            'email'                 => $email,
            'website'               => $website,
            'gambar1'               => $gambar1,
            'gambar2'               => $gambar2,
            'title'                 => 'Halaman SPJ',
            'routePrefix'           => 'master_spjs'
        ];

        // dd($invo);
        return view('master_spj.index', $data, compact('filteredData', 'sub_kategori_rkbus'), ['title' => 'Halaman SPJ', 'routePrefix' => 'master_spjs']);
    }

    public function create() {}
    public function store(Request $request) {}

    public function show($id)
    {
        $tahunAnggaran = Session::get('tahun_anggaran');
        $master_spj = MasterSpj::find($id);

        if (!$master_spj) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $judul_headers = JudulHeader::first();
        $pendukung_ppk = AdminPendukung::all();
        $perusahaan = Perusahaan::all();
        $invoice = Spj::where('id_spj', $master_spj->id_spj)->first();

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
            //->where('spj_details.id_usulan_barang', $invoice->id_usulan_barang) // Filter by id_usulan_barang
            ->select(
                'spj_details.*',
                'usulan_barangs.*',
                'usulan_barang_details.*',
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

        $detail_master_spj  = MasterSpj::join('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rekenings.id_sub_kategori_rekening', '=', 'sub_kategori_rkbus.id_sub_kategori_rekening')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('admin_pendukungs', 'admin_pendukungs.id_admin_pendukung_ppk', '=', 'master_spjs.id_admin_pendukung_ppk')
            ->join('pejabat_pengadaans', 'pejabat_pengadaans.id_pejabat_pengadaan', '=', 'admin_pendukungs.id_pejabat_pengadaan')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->where('spj_details.id_spj', $invoice->id_spj)
            ->select(
                'master_spjs.*',
                'perusahaans.*',
                'admin_pendukungs.id_admin_pendukung_ppk',
                'kategori_rkbus.*',
                'sub_kategori_rkbus.*',
                'rekening_belanjas.*',
                'pejabat_pengadaans.*'
            )
            ->distinct()
            ->first();


        $tgl_bast               = $detail_master_spj->tgl_bast;
        $tgl_pembayaran         = $master_spj->tanggal_pembayaran;
        $tgl_surat_pesanan      = $detail_master_spj->tgl_surat_pesanan;
        $tgl_waktu_pekerjaan    = $detail_master_spj->jangka_waktu_pekerjaan;


        $tgl_pesanan            = formatTanggal($tgl_surat_pesanan);
        $tgl_pembayaran         = formatTanggal($tgl_pembayaran);
        $tgl_bast               = formatTanggal($tgl_bast);
        $tgl_pekerjaan          = formatTanggal($tgl_waktu_pekerjaan);
        $terbilang_total        = terbilangUang($sum_total_anggaran_usulan_barang + $sum_total_ppn);

        // Menghitung selisih hari
        $tanggal1 = Carbon::parse($detail_master_spj->tgl_surat_pesanan);
        $tanggal2 = Carbon::parse($detail_master_spj->jangka_waktu_pekerjaan);
        $selisih_hari = $tanggal2->diffInDays($tanggal1);
        $jumlah_hari =  $selisih_hari;
        $jumlah_hari_terbilang = terbilang($jumlah_hari);

        // dd($detail_master_spj);

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');


        if ($invoice) {
            $keranjang = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
                ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
                ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
                ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->select(
                    'spj_details.*',
                    'rkbus.nama_barang',
                    'usulan_barang_details.*',
                    'usulan_barangs.no_usulan_barang',
                    'kategori_rkbus.kode_kategori_rkbu',
                    'kategori_rkbus.nama_kategori_rkbu',
                    'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                    'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                ) // Select kolom yang dibutuhkan
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }

        // dd($keranjang);

        $data = [
            'no_inv'                          => $invo,
            'master_spj'                      => $master_spj,
            'detail_master_spj'               => $detail_master_spj,
            'pendukung'                       => $pendukung_ppk,
            'perusahaan'                      => $perusahaan,
            'tgl_pesanan'                     => $tgl_pesanan,
            'tgl_pembayaran'                  => $tgl_pembayaran,
            'tgl_bast'                        => $tgl_bast,
            'tgl_pekerjaan'                   => $tgl_pekerjaan,
            'terbilang_total'                 => $terbilang_total,
            'jumlah_hari_terbilang'           => $jumlah_hari_terbilang,
            'judul_header1'                   => $judul_header1,
            'keranjang'                       => $keranjang,
            'get_total'                       => $sum_total_anggaran_usulan_barang,
            'ppn'                             => $sum_total_ppn,
            'nama'                            => $nama,
            'alamat'                          => $alamat,
            'tlp'                             => $tlp,
            'email'                           => $email,
            'website'                         => $website,
            'gambar1'                         => $gambar1,
            'gambar2'                         => $gambar2,
        ];

        // dd($master_spj_all);
        try {
            // Semua kode Anda di sini
            return view('master_spj.modal_detail_spj', $data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit($id_master_spj)
    {
        $tahunAnggaran          = Session::get('tahun_anggaran');
        $master_spj             = MasterSpj::findOrFail($id_master_spj);
        $judul_headers          = JudulHeader::first();
        $pendukung_ppk          = AdminPendukung::all();
        $perusahaan             = Perusahaan::all();
        $invoice                = Spj::where('id_spj', $master_spj->id_spj)->first();
        $id_user_pendukung_ppk  = optional($master_spj->adminPendukung)->user->id_user ?? 'Tidak Ada';

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

        $sub_kategori_rkbus     = MasterSpj::where('master_spjs.id_spj', $invoice->id_spj)
            ->join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            //->where('usulan_barangs.id_sub_kategori_rkbu', $invoice)
            ->select('sub_kategori_rkbus.*', 'usulan_barangs.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->get();

        // dd($sub_kategori_rkbus, $invoice);


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
            //->where('spj_details.id_usulan_barang', $invoice->id_usulan_barang) // Filter by id_usulan_barang
            ->select(
                'spj_details.*',
                'usulan_barangs.no_usulan_barang',
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

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');

        if ($invoice) {
            $keranjang = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
                ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
                ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu') // Join ke tabel usulan_barangs
                ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
                ->select(
                    'spj_details.*',
                    'rkbus.nama_barang',
                    'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                    'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                    'usulan_barang_details.*'
                ) // Select kolom yang dibutuhkan
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }

        $data = [
            'no_inv'                          => $invo,
            'master_spj'                      => $master_spj,
            'sub_kategori_rkbus'              => $sub_kategori_rkbus,
            'pendukung'                       => $pendukung_ppk,
            'perusahaan'                      => $perusahaan,
            'judul_header1'                   => $judul_header1,
            'keranjang'                       => $keranjang,
            'get_total'                       => $sum_total_anggaran_usulan_barang,
            'ppn'                             => $sum_total_ppn,
            'nama'                            => $nama,
            'alamat'                          => $alamat,
            'tlp'                             => $tlp,
            'email'                           => $email,
            'website'                         => $website,
            'gambar1'                         => $gambar1,
            'gambar2'                         => $gambar2,
            'title'                           => ''
        ];


        // dd($data);
        return view('master_spj.edit', $data, compact('master_spj'), ['title' => '', 'routePrefix' => 'master_spjs']);
    }

    public function editkomponen($id)
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
        return view('master_spj.edit_komponen', compact('usulan_barang_details', 'rkbu', 'uraian_satu', 'uraian_dua', 'invo'), ['title' => 'Halaman SPJ', 'routePrefix' => 'master_spjs']);
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

            return redirect()->route('master_spjs.index')
                ->with('success', 'Detail usulan barang berhasil diperbarui.');
        } else {
            // Jika total anggaran setelah update melebihi, tampilkan alert
            return redirect()->back()->with('lebih', 'Total Usulan melebihi Pagu Anggaran.');
        }
    }

    public function updateSuratPesanan(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Bersihkan format currency sebelum validasi
        $request->merge([
            'bruto'       => str_replace('.', '', $request->bruto),
            'harga_dasar' => str_replace('.', '', $request->harga_dasar),
        ]);

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'rincian_belanja'           => 'required|string|max:5000',
                'jangka_waktu_pekerjaan'    => 'required|date',
                'tgl_surat_pesanan'         => 'required|date',
                'no_surat_pesanan'          => 'required|string|max:255',
                'idpaket'                   => 'required|string|max:255',
                'id_admin_pendukung_ppk'    => 'required',
                'id_perusahaan'             => 'required',
                'bruto'                     => 'required|numeric|min:0',
                'ppn'                       => 'required|numeric|min:0|max:100',
                'harga_dasar'               => 'nullable|numeric|min:0',
                'status_hutang'             => 'nullable',

            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->rincian_belanja             = $request->input('rincian_belanja');
        $masterSpj->jangka_waktu_pekerjaan      = $request->input('jangka_waktu_pekerjaan');
        $masterSpj->tgl_surat_pesanan           = $request->input('tgl_surat_pesanan');
        $masterSpj->no_surat_pesanan            = $request->input('no_surat_pesanan');
        $masterSpj->idpaket                     = $request->input('idpaket');
        $masterSpj->rincian_belanja             = $request->input('rincian_belanja');
        $masterSpj->id_admin_pendukung_ppk      = $request->input('id_admin_pendukung_ppk');
        $masterSpj->id_perusahaan               = $request->input('id_perusahaan');
        $masterSpj->bruto                       = $request->input('bruto');
        $masterSpj->ppn                         = $request->input('ppn');
        $masterSpj->harga_dasar                 = $request->input('harga_dasar');
        $masterSpj->status_hutang               = $request->input('status_hutang');
        $masterSpj->status_proses_pesanan       = 'Selesai';
        $masterSpj->ket_verif_bendahara         = 'Proses';
        $masterSpj->tgl_proses_pemesanan        = now();


        $masterSpj->save();

        if ($masterSpj->status_hutang === 'Hutang') {
            // Cari id_usulan_barang dari spj_details yang terkait dengan id_spj
            $idUsulanBarang = SpjDetail::where('id_spj', $masterSpj->id_spj)->pluck('id_usulan_barang');

            // Update status_komponen_spj di tabel usulan_barang_details
            UsulanBarangDetail::whereIn('id_usulan_barang', $idUsulanBarang)
                ->update(['status_komponen_spj' => 'Hutang']);
        }


        return redirect()->route('master_spjs.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateBarangDatang(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'keterangan_barang_datang'      => 'nullable|string|max:5000',
                'foto_barang_datang'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_2'          => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_3'          => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'keterangan_barang_datang_parsial_1'      => 'nullable|string|max:5000',
                'foto_barang_datang_1_parsial_1' => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_2_parsial_1' => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_3_parsial_1' => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'tgl_barang_datang_parsial_1'    => 'nullable|date',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->errors()->first());
        }

        // Ambil data MasterSpj berdasarkan ID
        $masterSpj = MasterSpj::findOrFail($id);

        // Update keterangan barang datang
        $masterSpj->keterangan_barang_datang = $request->input('keterangan_barang_datang');

        // Path penyimpanan file
        $uploadPath = 'spjs/barang_datang';

        // Proses upload foto jika ada file
        if ($request->hasFile('foto_barang_datang')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang && Storage::exists('public/' . $masterSpj->foto_barang_datang)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang = $request->file('foto_barang_datang')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_2')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_2 && Storage::exists('public/' . $masterSpj->foto_barang_datang_2)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_2);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_2 = $request->file('foto_barang_datang_2')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_3')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_3 && Storage::exists('public/' . $masterSpj->foto_barang_datang_3)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_3);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_3 = $request->file('foto_barang_datang_3')->store($uploadPath, 'public');
        }

        if ($request->input('status_proses_pengiriman_barang') === 'Parsial 1') {
            if ($request->hasFile('foto_barang_datang_1_parsial_1')) {
                $masterSpj->foto_barang_datang_1_parsial_1 = $request->file('foto_barang_datang_1_parsial_1')->store($uploadPath, 'public');
            }
            if ($request->hasFile('foto_barang_datang_2_parsial_1')) {
                $masterSpj->foto_barang_datang_2_parsial_1 = $request->file('foto_barang_datang_2_parsial_1')->store($uploadPath, 'public');
            }
            if ($request->hasFile('foto_barang_datang_3_parsial_1')) {
                $masterSpj->foto_barang_datang_3_parsial_1 = $request->file('foto_barang_datang_3_parsial_1')->store($uploadPath, 'public');
            }

            $masterSpj->tgl_barang_datang_parsial_1 = $request->input('tgl_barang_datang_parsial_1');
        }

        // Update status dan tanggal barang datang
        $masterSpj->status_proses_pengiriman_barang    = $request->input('status_proses_pengiriman_barang');
        $masterSpj->tgl_barang_datang = now();

        // Simpan perubahan
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function UpdateParsial1(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        $validator = Validator::make(
            $request->all(),
            [

                'keterangan_barang_datang_parsial_1'        => 'nullable|string|max:5000',
                'foto_barang_datang_1_parsial_1'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_2_parsial_1'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_3_parsial_1'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'tgl_barang_datang_parsial_1'               => 'nullable|date',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->errors()->first());
        }

        // Ambil data MasterSpj berdasarkan ID
        $masterSpj = MasterSpj::findOrFail($id);

        // Update keterangan barang datang
        $masterSpj->keterangan_barang_datang_parsial_1 = $request->input('keterangan_barang_datang_parsial_1');

        // Path penyimpanan file
        $uploadPath = 'spjs/barang_datang';

        // Proses upload foto jika ada file
        if ($request->hasFile('foto_barang_datang_1_parsial_1')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_1_parsial_1 && Storage::exists('public/' . $masterSpj->foto_barang_datang_1_parsial_1)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_1_parsial_1);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_1_parsial_1 = $request->file('foto_barang_datang_1_parsial_1')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_2_parsial_1')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_2_parsial_1 && Storage::exists('public/' . $masterSpj->foto_barang_datang_2_parsial_1)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_2_parsial_1);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_2_parsial_1 = $request->file('foto_barang_datang_2_parsial_1')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_3_parsial_1')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_3_parsial_1 && Storage::exists('public/' . $masterSpj->foto_barang_datang_3_parsial_1)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_3_parsial_1);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_3_parsial_1 = $request->file('foto_barang_datang_3_parsial_1')->store($uploadPath, 'public');
        }

        // Update status dan tanggal barang datang
        $masterSpj->status_proses_pengiriman_barang    = $request->input('status_proses_pengiriman_barang');
        $masterSpj->tgl_barang_datang_parsial_1 = now();

        // Simpan perubahan
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function UpdateParsial2(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        $validator = Validator::make(
            $request->all(),
            [

                'keterangan_barang_datang_parsial_2'        => 'nullable|string|max:5000',
                'foto_barang_datang_1_parsial_2'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_2_parsial_2'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'foto_barang_datang_3_parsial_2'            => 'nullable|image|mimes:jpeg,png,jpg|max:800',
                'tgl_barang_datang_parsial_2'               => 'nullable|date',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->errors()->first());
        }

        // Ambil data MasterSpj berdasarkan ID
        $masterSpj = MasterSpj::findOrFail($id);

        // Update keterangan barang datang
        $masterSpj->keterangan_barang_datang_parsial_2 = $request->input('keterangan_barang_datang_parsial_2');

        // Path penyimpanan file
        $uploadPath = 'spjs/barang_datang';

        // Proses upload foto jika ada file
        if ($request->hasFile('foto_barang_datang_1_parsial_2')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_1_parsial_2 && Storage::exists('public/' . $masterSpj->foto_barang_datang_1_parsial_2)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_1_parsial_2);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_1_parsial_2 = $request->file('foto_barang_datang_1_parsial_2')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_2_parsial_2')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_2_parsial_2 && Storage::exists('public/' . $masterSpj->foto_barang_datang_2_parsial_2)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_2_parsial_2);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_2_parsial_2 = $request->file('foto_barang_datang_2_parsial_2')->store($uploadPath, 'public');
        }

        if ($request->hasFile('foto_barang_datang_3_parsial_2')) {
            // Hapus file lama jika ada
            if ($masterSpj->foto_barang_datang_3_parsial_2 && Storage::exists('public/' . $masterSpj->foto_barang_datang_3_parsial_2)) {
                Storage::delete('public/' . $masterSpj->foto_barang_datang_3_parsial_2);
            }

            // Simpan file baru
            $masterSpj->foto_barang_datang_3_parsial_2 = $request->file('foto_barang_datang_3_parsial_2')->store($uploadPath, 'public');
        }

        // Update status dan tanggal barang datang
        $masterSpj->status_proses_pengiriman_barang    = $request->input('status_proses_pengiriman_barang');
        $masterSpj->tgl_barang_datang_parsial_2 = now();

        // Simpan perubahan
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function tukarfaktur(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'tanggal_faktur'                => 'required|date',
                'tgl_kwitansi'              => 'required|date',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->tanggal_faktur                  = $request->input('tanggal_faktur');
        $masterSpj->tgl_kwitansi                = $request->input('tgl_kwitansi');
        $masterSpj->status_proses_tukar_faktur  = 'Selesai';
        $masterSpj->tgl_proses_faktur           = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateBast(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'tgl_bast'            => 'required|date',
                'no_ba'               => 'required|string',
                'no_ba_hp'            => 'required|string',
                'no_ba_bp'            => 'required|string',
                'no_dpa'              => 'required|string',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->tgl_bast             = $request->input('tgl_bast');
        $masterSpj->no_ba                = $request->input('no_ba');
        $masterSpj->no_ba_hp             = $request->input('no_ba_hp');
        $masterSpj->no_ba_bp             = $request->input('no_ba_bp');
        $masterSpj->no_dpa               = $request->input('no_dpa');
        $masterSpj->status_proses_bast   = 'Selesai';
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifPB(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'status_verifikasi_pengurus_barang'        => 'required|string|max:5000',
                'keterangan_verif_pengurus_barang'         => 'required|string|max:5000',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->status_verifikasi_pengurus_barang = $request->input('status_verifikasi_pengurus_barang');
        $masterSpj->keterangan_verif_pengurus_barang  = $request->input('keterangan_verif_pengurus_barang');
        $masterSpj->tgl_verif_pengurus_barang         = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifPPK(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_ppk'             => 'nullable|string',
                'status_verifikasi_ppk'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_ppk               = $request->input('ket_verif_ppk');
        $masterSpj->status_verifikasi_ppk       = $request->input('status_verifikasi_ppk');
        $masterSpj->tgl_verif                   = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifPPBJ(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_ppbj'             => 'nullable|string',
                'status_verifikasi_ppbj'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_ppbj               = $request->input('ket_verif_ppbj');
        $masterSpj->status_verifikasi_ppbj       = $request->input('status_verifikasi_ppbj');
        $masterSpj->tgl_verif_ppbj               = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifPPTK(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_pptk'             => 'nullable|string',
                'status_verifikasi_pptk'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_pptk               = $request->input('ket_verif_pptk');
        $masterSpj->status_verifikasi_pptk       = $request->input('status_verifikasi_pptk');
        $masterSpj->tgl_verif_pptk               = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifVerifikator(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_verifikator'             => 'nullable|string',
                'status_verifikasi_verifikator'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_verifikator               = $request->input('ket_verif_verifikator');
        $masterSpj->status_verifikasi_verifikator       = $request->input('status_verifikasi_verifikator');
        $masterSpj->tgl_verif_verifikator               = now()->toDateString();
        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifPPKKeuangan(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_ppk_keuangan'             => 'nullable|string',
                'status_verifikasi_ppk_keuangan'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_ppk_keuangan               = $request->input('ket_verif_ppk_keuangan');
        $masterSpj->status_verifikasi_ppk_keuangan       = $request->input('status_verifikasi_ppk_keuangan');

        // Cek status verifikasi
        if ($request->input('status_verifikasi_ppk_keuangan') === 'Revisi') {
            $masterSpj->tgl_revisi_spj_ppk_keuangan = now()->toDateString();
        } else {
            $masterSpj->tgl_verif_ppk_keuangan = now()->toDateString();
        }

        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateVerifDirektur(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'ket_verif_direktur'             => 'nullable|string',
                'status_verifikasi_direktur'     => 'required',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);

        $masterSpj->ket_verif_direktur               = $request->input('ket_verif_direktur');
        $masterSpj->status_verifikasi_direktur       = $request->input('status_verifikasi_direktur');

        // Cek status verifikasi
        if ($request->input('status_verifikasi_direktur') === 'Revisi') {
            $masterSpj->tgl_revisi_spj_direktur = now()->toDateString();
        } else {
            $masterSpj->tgl_verif_direktur = now()->toDateString();
        }

        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateSerahTerimaBendahara(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'status_serah_terima_bendahara'     => 'required',
                'bruto'                             => 'required',
                'ppn'                               => 'nullable',
                'harga_dasar'                       => 'required',
                'bulan_penyerahan_spj'              => 'required',
                'tanggal_penyerahan_spj'            => 'required',
                'upload_spj_1'                      => 'nullable|file|mimes:pdf|max:20480',
                'upload_spj_2'                      => 'nullable|file|mimes:pdf|max:20480',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);
        $tanggalPenyerahanSpj = $request->input('tanggal_penyerahan_spj');
        $bulanPenyerahanSpj = Carbon::parse($tanggalPenyerahanSpj)->translatedFormat('F');

        // Path penyimpanan file
        $uploadPath = 'spjs/dokumen_spj';

        // Proses upload foto jika ada file
        if ($request->hasFile('upload_spj_1')) {
            // Hapus file lama jika ada
            if ($masterSpj->upload_spj_1 && Storage::exists('public/' . $masterSpj->upload_spj_1)) {
                Storage::delete('public/' . $masterSpj->upload_spj_1);
            }

            // Simpan file baru
            $masterSpj->upload_spj_1 = $request->file('upload_spj_1')->store($uploadPath, 'public');
        }

        // Proses upload foto jika ada file
        if ($request->hasFile('upload_spj_2')) {
            // Hapus file lama jika ada
            if ($masterSpj->upload_spj_2 && Storage::exists('public/' . $masterSpj->upload_spj_2)) {
                Storage::delete('public/' . $masterSpj->upload_spj_2);
            }

            // Simpan file baru
            $masterSpj->upload_spj_2 = $request->file('upload_spj_2')->store($uploadPath, 'public');
        }

        $masterSpj->status_serah_terima_bendahara     = $request->input('status_serah_terima_bendahara');
        $masterSpj->bruto                             = $request->input('bruto');
        $masterSpj->ppn                               = $request->input('ppn');
        $masterSpj->harga_dasar                       = $request->input('harga_dasar');
        $masterSpj->bulan_penyerahan_spj              = $bulanPenyerahanSpj;
        $masterSpj->tanggal_penyerahan_spj            = $tanggalPenyerahanSpj;

        // Cek status verifikasi
        if ($request->input('status_serah_terima_bendahara') === 'Revisi') {
            $masterSpj->tanggal_revisi_spj = now()->toDateString();
        } else {
            $masterSpj->tanggal_revisi_spj = now()->toDateString();
        }

        // dd($masterSpj);

        $masterSpj->save();

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function updateverifikasiBendahara(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'status_pembayaran'                 => 'nullable|string',
                'bruto'                             => 'required',
                'ppn'                               => 'nullable',
                'pph22'                             => 'nullable',
                'pph23'                             => 'nullable',
                'pph21'                             => 'nullable',
                'pp05'                              => 'nullable',
                'jumlah_pajak'                      => 'nullable',
                'harga_bersih'                      => 'nullable',
                'bpjs_kes'                          => 'nullable',
                'bpjs_tk'                           => 'nullable',
                'pembayaran'                        => 'nullable',
                'sisa_pembayaran'                   => 'nullable',
                'kode_billingppn'                   => 'nullable',
                'kode_billingpph22'                 => 'nullable',
                'harga_dasar'                       => 'required',
                'bulan_penyerahan_spj'              => 'required',
                'tanggal_penyerahan_spj'            => 'required',
                'bukti_bayar'                       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'nama_validasi_keuangan'            => 'required',
                'tanggal_pembayaran'                => 'required',
                'bulan_pembayaran'                  => 'required',
                'ket_verif_bendahara'               => 'nullable',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $masterSpj = MasterSpj::findOrFail($id);
        $tanggalPenyerahanSpj = $request->input('tanggal_penyerahan_spj');
        $bulanPenyerahanSpj = Carbon::parse($tanggalPenyerahanSpj)->translatedFormat('F');

        // Path penyimpanan file
        $uploadPath = 'spjs/bukti_bayar';

        // Proses upload foto jika ada file
        if ($request->hasFile('bukti_bayar')) {
            // Hapus file lama jika ada
            if ($masterSpj->bukti_bayar && Storage::exists('public/' . $masterSpj->bukti_bayar)) {
                Storage::delete('public/' . $masterSpj->bukti_bayar);
            }

            // Simpan file baru
            $masterSpj->bukti_bayar = $request->file('bukti_bayar')->store($uploadPath, 'public');
        }

        $bruto = str_replace('.', '', $request->input('bruto')); // Menghapus pemisah ribuan
        $bruto = floatval($bruto);

        $harga_bersih = str_replace('.', '', $request->input('harga_bersih')); // Menghapus pemisah ribuan
        $harga_bersih = floatval($harga_bersih);

        $jumlah_pajak = str_replace('.', '', $request->input('jumlah_pajak')); // Menghapus pemisah ribuan
        $jumlah_pajak = floatval($jumlah_pajak);

        $sisa_pembayaran = str_replace('.', '', $request->input('sisa_pembayaran')); // Menghapus pemisah ribuan
        $sisa_pembayaran = floatval($sisa_pembayaran);

        $masterSpj->status_pembayaran                        = $request->input('status_pembayaran');
        $masterSpj->nama_validasi_keuangan            = $request->input('nama_validasi_keuangan');
        $masterSpj->bruto                             = $bruto;
        $masterSpj->ppn                               = $request->input('ppn');
        $masterSpj->pph22                             = $request->input('pph22');
        $masterSpj->pph23                             = $request->input('pph23');
        $masterSpj->pph21                             = $request->input('pph21');
        $masterSpj->pp05                              = $request->input('pp05');
        $masterSpj->jumlah_pajak                      = $jumlah_pajak;
        $masterSpj->harga_bersih                      = $harga_bersih;
        $masterSpj->bpjs_kes                          = $request->input('bpjs_kes');
        $masterSpj->bpjs_tk                           = $request->input('bpjs_tk');
        $masterSpj->pembayaran                        = $request->input('pembayaran');
        $masterSpj->sisa_pembayaran                   = $sisa_pembayaran;
        $masterSpj->kode_billingppn                   = $request->input('kode_billingppn');
        $masterSpj->kode_billingpph22                 = $request->input('kode_billingpph22');
        $masterSpj->harga_dasar                       = $request->input('harga_dasar');
        $masterSpj->bulan_penyerahan_spj              = $bulanPenyerahanSpj;
        $masterSpj->tanggal_penyerahan_spj            = $tanggalPenyerahanSpj;
        $masterSpj->tanggal_pembayaran                = $request->input('tanggal_pembayaran');
        $masterSpj->bulan_pembayaran                  = $request->input('bulan_pembayaran');
        $masterSpj->ket_verif_bendahara               = $request->input('ket_verif_bendahara');

        // Cek status verifikasi
        if ($request->input('status_pembayaran') === 'Revisi') {
            $masterSpj->tanggal_revisi_spj = now()->toDateString();
        } else {
            $masterSpj->tanggal_revisi_spj = now()->toDateString();
        }

        // dd($masterSpj);

        $masterSpj->save();

        // Update status_spj di tabel spjs (melalui relasi)
        if ($masterSpj->spj) {
            $masterSpj->spj->status_spj = 'Sudah di Bayar';
            $masterSpj->spj->save();
        }

        return redirect()->route('master_spj.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id_spj_detail)
    {
        try {
            // Cari data spj_detail berdasarkan id_spj_detail
            $detail = SpjDetail::findOrFail($id_spj_detail);

            // Hapus data
            $detail->delete();

            return redirect()
                ->back()
                ->with('success', 'Data detail berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus detail: ' . $e->getMessage());
        }
    }
}
