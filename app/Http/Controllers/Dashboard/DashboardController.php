<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingInput\Anggaran;

class DashboardController extends Controller
{
    public function index()
    {

        // dd(session()->all());
        $id_user        = session('id_user');
        $id_ksp         = session('id_ksp');
        $id_pejabat     = session('id_pejabat');
        $tahunAnggaran  = session('tahun_anggaran');

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->where('pejabats.id_pejabat', $id_pejabat)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                ->sum('total_anggaran');
        };

        // Total anggaran per kabag
        $total_anggaran_pegawai_kabag  = $query('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_kabag  = $query('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_kabag = $query('9cf603bb-bfd0-4b1e-8a24-7339459d9507');
        $total_anggaran_kabag = $total_anggaran_pegawai_kabag + $total_anggaran_modal_kabag + $total_anggaran_barjas_kabag;

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_ksp = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->where('ksps.id_ksp', session('id_ksp')) // Filter berdasarkan id_ksp dari session
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                ->sum('total_anggaran');
        };

        // Total anggaran per kabag
        $total_anggaran_pegawai_ksp  = $query_ksp('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_ksp  = $query_ksp('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_ksp = $query_ksp('9cf603bb-bfd0-4b1e-8a24-7339459d9507');
        $total_anggaran_ksp = $total_anggaran_pegawai_ksp + $total_anggaran_modal_ksp + $total_anggaran_barjas_ksp;
        $total_anggaran_operasi_ksp = $total_anggaran_pegawai_ksp + $total_anggaran_barjas_ksp;

        // Total anggaran per user
        $total_anggaran_user = Rkbu::where('id_user', $id_user)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)->sum('total_anggaran');

        $total_anggaran_modal_user  = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf603e2-e748-49f0-949f-6c3c30d42c3e')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_barjas_user = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf603bb-bfd0-4b1e-8a24-7339459d9507')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_pegawai_user = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf6040a-2759-4d16-a3cf-3eee5194a2d5')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3', // BLUD
            '9cdfced0-87ce-49dc-8268-25ed56420bf7', // APBD
            '9cf6ed93-0b31-4941-a31a-82d07eb81873' // DAK
        ];

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_admin = function ($id_kode_rekening_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->sum('total_anggaran');
        };


        $query_admin_jenis_belanja = function ($id_jenis_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
                ->join('jenis_belanjas', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('jenis_belanjas.id_jenis_belanja', $id_jenis_belanja)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->sum('total_anggaran');
        };

        $query_pagu = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            return Anggaran::where('tahun_anggaran', $tahunAnggaran)
                ->where('id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->select('jumlah_anggaran')
                ->first(); // Ambil data pertama dari query
        };


        // Ambil nilai jumlah_anggaran untuk masing-masing id_kode_rekening_belanja
        $pagu_pegawai   = optional($query_pagu('9cf6040a-2759-4d16-a3cf-3eee5194a2d5'))->jumlah_anggaran;
        $pagu_modal     = optional($query_pagu('9cf603e2-e748-49f0-949f-6c3c30d42c3e'))->jumlah_anggaran;
        $pagu_barjas    = optional($query_pagu('9cf603bb-bfd0-4b1e-8a24-7339459d9507'))->jumlah_anggaran;
        $total_pagu     = $pagu_pegawai + $pagu_modal + $pagu_barjas;

        $id_kode_rekening_belanja_non_barjas = [
            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas = [
            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        // Total anggaran per kabag
        $total_anggaran_pegawai_admin  = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin  = $query_admin('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_admin = $query_admin($id_kode_rekening_belanja_barjas);
        $total_anggaran_admin = $total_anggaran_pegawai_admin + $total_anggaran_modal_admin + $total_anggaran_barjas_admin;

        // Total anggaran per Jenis Belanja
        $total_anggaran_jenis_pegawai_admin         = $query_admin_jenis_belanja('9cdfcfed-6061-485b-8e20-662a776d0d06');
        $total_anggaran_jenis_barjas_admin          = $query_admin_jenis_belanja('9cdfd042-e7cc-4008-ad0e-96d0a5452721');
        $total_anggaran_jenis_subsidi_admin         = $query_admin_jenis_belanja('9cf5eb80-459b-49f2-8c93-4c2597b14f1a');
        $total_anggaran_jenis_bunga_admin           = $query_admin_jenis_belanja('9cf5eb68-c921-40f4-8b74-208ef83f5a13');
        $total_anggaran_jenis_modal_tanah_admin     = $query_admin_jenis_belanja('9cf5ec04-b0f6-4b00-8246-bfa4996bce6b');
        $total_anggaran_jenis_modal_mesin_admin     = $query_admin_jenis_belanja('9cf5ec2e-e7c6-4cdd-8c41-24a7cd5a594e');
        $total_anggaran_jenis_modal_gedung_admin    = $query_admin_jenis_belanja('9cf5ec45-f580-4f84-bab4-5252ac58a00c');
        $total_anggaran_jenis_admin                 = $total_anggaran_jenis_pegawai_admin +
            $total_anggaran_jenis_barjas_admin +
            $total_anggaran_jenis_subsidi_admin +
            $total_anggaran_jenis_bunga_admin +
            $total_anggaran_jenis_modal_tanah_admin +
            $total_anggaran_jenis_modal_gedung_admin +
            $total_anggaran_jenis_modal_mesin_admin;

        $belanja_operasi                            = $total_anggaran_jenis_pegawai_admin +  $total_anggaran_jenis_subsidi_admin + $total_anggaran_jenis_barjas_admin + $total_anggaran_jenis_bunga_admin;
        $belanja_modal_ringkasan                    = $total_anggaran_jenis_modal_tanah_admin + $total_anggaran_jenis_modal_mesin_admin + $total_anggaran_jenis_modal_gedung_admin;

        $selisih_pagu               = $total_pagu - $total_anggaran_admin;
        $selisih_pagu_pegawai       = $pagu_pegawai - $total_anggaran_pegawai_admin;
        $selisih_pagu_barjas        = $pagu_barjas - $total_anggaran_barjas_admin;
        $selisih_pagu_modal         = $pagu_modal - $total_anggaran_modal_admin;
        /////////////////////////////////////// BLUD ADMIN TERINPUT /////////////////////////////////////////////

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_admin_input_all = function ($id_kode_rekening_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->sum('total_anggaran');
        };


        $query_admin_jenis_belanja_input_all = function ($id_jenis_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
                ->join('jenis_belanjas', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('jenis_belanjas.id_jenis_belanja', $id_jenis_belanja)
                ->sum('total_anggaran');
        };

        $id_kode_rekening_belanja_non_barjas = [
            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas = [
            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        // Total anggaran per kabag
        $total_anggaran_pegawai_admin_all   = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin_all     = $query_admin('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_admin_all    = $query_admin($id_kode_rekening_belanja_barjas);
        $total_anggaran_admin_all           = $total_anggaran_pegawai_admin_all + $total_anggaran_modal_admin_all + $total_anggaran_barjas_admin_all;

        // Total anggaran per Jenis Belanja
        $total_anggaran_jenis_pegawai_admin_all         = $query_admin_jenis_belanja_input_all('9cdfcfed-6061-485b-8e20-662a776d0d06');
        $total_anggaran_jenis_barjas_admin_all          = $query_admin_jenis_belanja_input_all('9cdfd042-e7cc-4008-ad0e-96d0a5452721');
        $total_anggaran_jenis_subsidi_admin_all         = $query_admin_jenis_belanja_input_all('9cf5eb80-459b-49f2-8c93-4c2597b14f1a');
        $total_anggaran_jenis_bunga_admin_all           = $query_admin_jenis_belanja_input_all('9cf5eb68-c921-40f4-8b74-208ef83f5a13');
        $total_anggaran_jenis_modal_tanah_admin_all     = $query_admin_jenis_belanja_input_all('9cf5ec04-b0f6-4b00-8246-bfa4996bce6b');
        $total_anggaran_jenis_modal_mesin_admin_all     = $query_admin_jenis_belanja_input_all('9cf5ec2e-e7c6-4cdd-8c41-24a7cd5a594e');
        $total_anggaran_jenis_modal_gedung_admin_all    = $query_admin_jenis_belanja_input_all('9cf5ec45-f580-4f84-bab4-5252ac58a00c');
        $total_anggaran_jenis_admin_all                 = $total_anggaran_jenis_pegawai_admin_all +
            $total_anggaran_jenis_barjas_admin_all +
            $total_anggaran_jenis_subsidi_admin_all +
            $total_anggaran_jenis_bunga_admin_all +
            $total_anggaran_jenis_modal_tanah_admin_all +
            $total_anggaran_jenis_modal_gedung_admin_all +
            $total_anggaran_jenis_modal_mesin_admin_all;

        $belanja_operasi_all                            = $total_anggaran_jenis_pegawai_admin_all +  $total_anggaran_jenis_subsidi_admin_all + $total_anggaran_jenis_barjas_admin_all + $total_anggaran_jenis_bunga_admin_all;
        $belanja_modal_ringkasan_all                    = $total_anggaran_jenis_modal_tanah_admin_all + $total_anggaran_jenis_modal_mesin_admin_all + $total_anggaran_jenis_modal_gedung_admin_all;
        $belanja_total_all                              = $belanja_operasi_all + $belanja_modal_ringkasan_all;

        /////////////////////////////////////// APBD ///////////////////////////////////////////////////////////
        $pegawai_apbd = ('9cf6025b-eb56-0000-0000-17e74056b7f5');

        $barjas_apbd = [
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e'
        ];

        $modal_apbd = [
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
            '9da0eda4-1669-47b2-aea6-a7f5ea1a79de',
            '9da0edde-1da7-4ea2-9932-5561c75a4c67',
            '9da0ee14-3182-427a-aded-ca2d74729850',
            '9da0ee44-f06e-4aa6-b6d8-6161dbb78b6e',
            '9da0ee72-0697-4631-88c3-9ee16eb1ba08',

            '9da0eea5-26e4-4ebb-8c55-579c8f9392bc',
            '9da0eeca-269d-4b18-95f1-d48b7b246f22',
            '9df8b9ab-6868-4f2f-9f92-986786d8dc92',
            '9e2dab58-8ce3-4d07-8404-4e4c7bbd568a'
        ];

        $aktivitasmodal = ['9cf5e38f-a302-45eb-a1b8-8d0d0e4e5e0f'];

        // Query function to handle both single ID and array of IDs for APBD calculations
        $query_apbd = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('pejabats.id_pejabat', $id_pejabat)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        // Calculate total budgets per category
        $total_anggaran_pegawai_kabag_apbd = $query_apbd($pegawai_apbd);
        $total_anggaran_modal_kabag_apbd = $query_apbd($modal_apbd);
        $total_anggaran_barjas_kabag_apbd = $query_apbd($barjas_apbd);
        $total_anggaran_kabag_apbd = $total_anggaran_pegawai_kabag_apbd + $total_anggaran_modal_kabag_apbd + $total_anggaran_barjas_kabag_apbd;

        // Additional query function for KSP
        $query_ksp_apbd = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->where('ksps.id_ksp', session('id_ksp'))
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        // Calculate total budgets for KSP
        $total_anggaran_pegawai_ksp_apbd = $query_ksp_apbd($pegawai_apbd);
        $total_anggaran_modal_ksp_apbd = $query_ksp_apbd($modal_apbd);
        $total_anggaran_barjas_ksp_apbd = $query_ksp_apbd($barjas_apbd);
        $total_anggaran_ksp_apbd = $total_anggaran_pegawai_ksp_apbd + $total_anggaran_modal_ksp_apbd + $total_anggaran_barjas_ksp_apbd;

        // Query for user totals
        $total_anggaran_user_apbd = Rkbu::join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
            ->where('id_user', $id_user)
            ->sum('total_anggaran');

        $total_anggaran_modal_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereIn('id_kode_rekening_belanja', $modal_apbd)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_barjas_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereIn('id_kode_rekening_belanja', $barjas_apbd)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_pegawai_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereNull('id_kode_rekening_belanja')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        // Query for admin totals
        $query_admin_apbd = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        $total_anggaran_pegawai_admin_apbd = $query_admin_apbd($pegawai_apbd);
        $total_anggaran_modal_admin_apbd = $query_admin_apbd($modal_apbd);
        $total_anggaran_barjas_admin_apbd = $query_admin_apbd($barjas_apbd);
        $total_anggaran_admin_apbd = $total_anggaran_pegawai_admin_apbd + $total_anggaran_modal_admin_apbd + $total_anggaran_barjas_admin_apbd;

        // Pagu calculation and differences
        $query_pagu_apbd = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            return Anggaran::where('tahun_anggaran', $tahunAnggaran)
                ->whereIn('id_kode_rekening_belanja', (array)$id_kode_rekening_belanja)
                ->sum('jumlah_anggaran');
        };

        $pagu_pegawai_apbd = $query_pagu_apbd($pegawai_apbd);
        $pagu_modal_apbd = $query_pagu_apbd($modal_apbd);
        $pagu_barjas_apbd = $query_pagu_apbd($barjas_apbd);
        $total_pagu_apbd = $pagu_pegawai_apbd + $pagu_modal_apbd + $pagu_barjas_apbd;

        $selisih_pagu_apbd = $total_pagu_apbd - $total_anggaran_admin_apbd;
        $selisih_pagu_pegawai_apbd = $pagu_pegawai_apbd - $total_anggaran_pegawai_admin_apbd;
        $selisih_pagu_barjas_apbd = $pagu_barjas_apbd - $total_anggaran_barjas_admin_apbd;
        $selisih_pagu_modal_apbd = $pagu_modal_apbd - $total_anggaran_modal_admin_apbd;

        $total_apbd_blud            = $total_anggaran_admin_apbd + $total_anggaran_admin;
        $total_apbd_blud_pegawai    = $total_anggaran_pegawai_admin + $total_anggaran_pegawai_admin_apbd;
        $total_apbd_blud_barjas     = $total_anggaran_barjas_admin + $total_anggaran_barjas_admin_apbd;
        $total_apbd_blud_modal      = $total_anggaran_modal_admin + $total_anggaran_modal_admin_apbd;

        $total_unit = DB::table('rkbus')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user') // Relasi rkbus -> users
            ->join('units', 'users.id_unit', '=', 'units.id_unit') // Relasi users -> units
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->select(
                'units.nama_unit',
                DB::raw('SUM(rkbus.total_anggaran) as total_anggaran_terinput'),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_perlu_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1edc-2263-401f-b249-361db4017932' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1f45-ca37-4bd3-8dc9-514c6b9f436c' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_ditolak"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_perlu_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1fa1-d0de-4e99-a368-8c218deda960' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_ditolak")
            )
            ->groupBy('units.nama_unit')
            ->get();

        $programs = Program::whereHas('kegiatans.sub_kegiatans.aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
            // Ambil hanya data RKBU yang tersedia dan sesuai tahun anggaran
            $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');
        })
            ->with([
                'kegiatans' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('sub_kegiatans.aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass.rekening_belanja' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran)
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass.rekening_belanja.rkbus'
            ])
            ->get();


        // return response()->json($programs);
        return view('dashboard.dashboard_index', compact(
            'total_anggaran_user',
            'total_unit',
            'programs',
            'total_anggaran_ksp',
            'total_anggaran_kabag',
            'total_anggaran_admin',
            'total_anggaran_modal_user',
            'total_anggaran_barjas_user',
            'total_anggaran_pegawai_user',
            'total_anggaran_modal_ksp',
            'total_anggaran_barjas_ksp',
            'total_anggaran_pegawai_ksp',
            'total_anggaran_modal_kabag',
            'total_anggaran_barjas_kabag',
            'total_anggaran_pegawai_kabag',
            'total_anggaran_modal_admin',
            'total_anggaran_barjas_admin',
            'total_anggaran_pegawai_admin',

            'total_anggaran_pegawai_admin_all',
            'total_anggaran_modal_admin_all',
            'total_anggaran_barjas_admin_all',
            'total_anggaran_admin_all',
            'total_anggaran_jenis_pegawai_admin_all',
            'total_anggaran_jenis_barjas_admin_all',
            'total_anggaran_jenis_subsidi_admin_all',
            'total_anggaran_jenis_bunga_admin_all',
            'total_anggaran_jenis_modal_tanah_admin_all',
            'total_anggaran_jenis_modal_mesin_admin_all',
            'total_anggaran_jenis_modal_gedung_admin_all',
            'total_anggaran_jenis_admin_all',
            'belanja_operasi_all',
            'belanja_modal_ringkasan_all',
            'belanja_total_all',

            'total_anggaran_user_apbd',
            'total_anggaran_ksp_apbd',
            'total_anggaran_kabag_apbd',
            'total_anggaran_admin_apbd',
            'total_anggaran_modal_user_apbd',
            'total_anggaran_barjas_user_apbd',
            'total_anggaran_pegawai_user_apbd',
            'total_anggaran_modal_ksp_apbd',
            'total_anggaran_barjas_ksp_apbd',
            'total_anggaran_pegawai_ksp_apbd',
            'total_anggaran_modal_kabag_apbd',
            'total_anggaran_barjas_kabag_apbd',
            'total_anggaran_pegawai_kabag_apbd',
            'total_anggaran_modal_admin_apbd',
            'total_anggaran_barjas_admin_apbd',
            'total_anggaran_pegawai_admin_apbd',
            'pagu_pegawai',
            'pagu_barjas',
            'pagu_modal',
            'total_pagu',
            'selisih_pagu',
            'selisih_pagu_pegawai',
            'selisih_pagu_barjas',
            'selisih_pagu_modal',
            'total_anggaran_jenis_pegawai_admin',
            'total_anggaran_jenis_barjas_admin',
            'total_anggaran_jenis_bunga_admin',
            'total_anggaran_jenis_modal_tanah_admin',
            'total_anggaran_jenis_modal_mesin_admin',
            'total_anggaran_jenis_modal_gedung_admin',
            'total_anggaran_jenis_admin',
            'belanja_operasi',
            'belanja_modal_ringkasan',
            'total_anggaran_operasi_ksp',
            'total_apbd_blud',
            'total_apbd_blud_pegawai',
            'total_apbd_blud_barjas',
            'total_apbd_blud_modal',

        ));
    }

    public function apbd()
    {

        // dd(session()->all());
        $id_user        = session('id_user');
        $id_ksp         = session('id_ksp');
        $id_pejabat     = session('id_pejabat');
        $tahunAnggaran  = session('tahun_anggaran');

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->where('pejabats.id_pejabat', $id_pejabat)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                ->sum('total_anggaran');
        };

        // Total anggaran per kabag
        $total_anggaran_pegawai_kabag  = $query('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_kabag  = $query('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_kabag = $query('9cf603bb-bfd0-4b1e-8a24-7339459d9507');
        $total_anggaran_kabag = $total_anggaran_pegawai_kabag + $total_anggaran_modal_kabag + $total_anggaran_barjas_kabag;

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_ksp = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->where('ksps.id_ksp', session('id_ksp')) // Filter berdasarkan id_ksp dari session
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                ->sum('total_anggaran');
        };

        // Total anggaran per kabag
        $total_anggaran_pegawai_ksp  = $query_ksp('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_ksp  = $query_ksp('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_ksp = $query_ksp('9cf603bb-bfd0-4b1e-8a24-7339459d9507');
        $total_anggaran_ksp = $total_anggaran_pegawai_ksp + $total_anggaran_modal_ksp + $total_anggaran_barjas_ksp;
        $total_anggaran_operasi_ksp = $total_anggaran_pegawai_ksp + $total_anggaran_barjas_ksp;

        // Total anggaran per user
        $total_anggaran_user = Rkbu::where('id_user', $id_user)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)->sum('total_anggaran');

        $total_anggaran_modal_user  = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf603e2-e748-49f0-949f-6c3c30d42c3e')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_barjas_user = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf603bb-bfd0-4b1e-8a24-7339459d9507')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_pegawai_user = Rkbu::where('id_user', $id_user)
            ->where('id_kode_rekening_belanja', '9cf6040a-2759-4d16-a3cf-3eee5194a2d5')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3', // BLUD
            '9cdfced0-87ce-49dc-8268-25ed56420bf7', // APBD
            '9cf6ed93-0b31-4941-a31a-82d07eb81873' // DAK
        ];

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_admin = function ($id_kode_rekening_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->sum('total_anggaran');
        };


        $query_admin_jenis_belanja = function ($id_jenis_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
                ->join('jenis_belanjas', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('jenis_belanjas.id_jenis_belanja', $id_jenis_belanja)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->sum('total_anggaran');
        };

        $query_pagu = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            return Anggaran::where('tahun_anggaran', $tahunAnggaran)
                ->where('id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->select('jumlah_anggaran')
                ->first(); // Ambil data pertama dari query
        };


        // Ambil nilai jumlah_anggaran untuk masing-masing id_kode_rekening_belanja
        $pagu_pegawai   = optional($query_pagu('9cf6040a-2759-4d16-a3cf-3eee5194a2d5'))->jumlah_anggaran;
        $pagu_modal     = optional($query_pagu('9cf603e2-e748-49f0-949f-6c3c30d42c3e'))->jumlah_anggaran;
        $pagu_barjas    = optional($query_pagu('9cf603bb-bfd0-4b1e-8a24-7339459d9507'))->jumlah_anggaran;
        $total_pagu     = $pagu_pegawai + $pagu_modal + $pagu_barjas;

        $id_kode_rekening_belanja_non_barjas = [
            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas = [
            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        // Total anggaran per kabag
        $total_anggaran_pegawai_admin  = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin  = $query_admin('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_admin = $query_admin($id_kode_rekening_belanja_barjas);
        $total_anggaran_admin = $total_anggaran_pegawai_admin + $total_anggaran_modal_admin + $total_anggaran_barjas_admin;

        // Total anggaran per Jenis Belanja
        $total_anggaran_jenis_pegawai_admin         = $query_admin_jenis_belanja('9cdfcfed-6061-485b-8e20-662a776d0d06');
        $total_anggaran_jenis_barjas_admin          = $query_admin_jenis_belanja('9cdfd042-e7cc-4008-ad0e-96d0a5452721');
        $total_anggaran_jenis_subsidi_admin         = $query_admin_jenis_belanja('9cf5eb80-459b-49f2-8c93-4c2597b14f1a');
        $total_anggaran_jenis_bunga_admin           = $query_admin_jenis_belanja('9cf5eb68-c921-40f4-8b74-208ef83f5a13');
        $total_anggaran_jenis_modal_tanah_admin     = $query_admin_jenis_belanja('9cf5ec04-b0f6-4b00-8246-bfa4996bce6b');
        $total_anggaran_jenis_modal_mesin_admin     = $query_admin_jenis_belanja('9cf5ec2e-e7c6-4cdd-8c41-24a7cd5a594e');
        $total_anggaran_jenis_modal_gedung_admin    = $query_admin_jenis_belanja('9cf5ec45-f580-4f84-bab4-5252ac58a00c');
        $total_anggaran_jenis_admin                 = $total_anggaran_jenis_pegawai_admin +
            $total_anggaran_jenis_barjas_admin +
            $total_anggaran_jenis_subsidi_admin +
            $total_anggaran_jenis_bunga_admin +
            $total_anggaran_jenis_modal_tanah_admin +
            $total_anggaran_jenis_modal_gedung_admin +
            $total_anggaran_jenis_modal_mesin_admin;

        $belanja_operasi                            = $total_anggaran_jenis_pegawai_admin +  $total_anggaran_jenis_subsidi_admin + $total_anggaran_jenis_barjas_admin + $total_anggaran_jenis_bunga_admin;
        $belanja_modal_ringkasan                    = $total_anggaran_jenis_modal_tanah_admin + $total_anggaran_jenis_modal_mesin_admin + $total_anggaran_jenis_modal_gedung_admin;

        $selisih_pagu               = $total_pagu - $total_anggaran_admin;
        $selisih_pagu_pegawai       = $pagu_pegawai - $total_anggaran_pegawai_admin;
        $selisih_pagu_barjas        = $pagu_barjas - $total_anggaran_barjas_admin;
        $selisih_pagu_modal         = $pagu_modal - $total_anggaran_modal_admin;
        /////////////////////////////////////// BLUD ADMIN TERINPUT /////////////////////////////////////////////

        // Query yang sama untuk ketiga sum berdasarkan id_kode_rekening_belanja
        $query_admin_input_all = function ($id_kode_rekening_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->sum('total_anggaran');
        };


        $query_admin_jenis_belanja_input_all = function ($id_jenis_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
                ->join('jenis_belanjas', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('jenis_belanjas.id_jenis_belanja', $id_jenis_belanja)
                ->sum('total_anggaran');
        };

        $id_kode_rekening_belanja_non_barjas = [
            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas = [
            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        // Total anggaran per kabag
        $total_anggaran_pegawai_admin_all   = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin_all     = $query_admin('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_admin_all    = $query_admin($id_kode_rekening_belanja_barjas);
        $total_anggaran_admin_all           = $total_anggaran_pegawai_admin_all + $total_anggaran_modal_admin_all + $total_anggaran_barjas_admin_all;

        // Total anggaran per Jenis Belanja
        $total_anggaran_jenis_pegawai_admin_all         = $query_admin_jenis_belanja_input_all('9cdfcfed-6061-485b-8e20-662a776d0d06');
        $total_anggaran_jenis_barjas_admin_all          = $query_admin_jenis_belanja_input_all('9cdfd042-e7cc-4008-ad0e-96d0a5452721');
        $total_anggaran_jenis_subsidi_admin_all         = $query_admin_jenis_belanja_input_all('9cf5eb80-459b-49f2-8c93-4c2597b14f1a');
        $total_anggaran_jenis_bunga_admin_all           = $query_admin_jenis_belanja_input_all('9cf5eb68-c921-40f4-8b74-208ef83f5a13');
        $total_anggaran_jenis_modal_tanah_admin_all     = $query_admin_jenis_belanja_input_all('9cf5ec04-b0f6-4b00-8246-bfa4996bce6b');
        $total_anggaran_jenis_modal_mesin_admin_all     = $query_admin_jenis_belanja_input_all('9cf5ec2e-e7c6-4cdd-8c41-24a7cd5a594e');
        $total_anggaran_jenis_modal_gedung_admin_all    = $query_admin_jenis_belanja_input_all('9cf5ec45-f580-4f84-bab4-5252ac58a00c');
        $total_anggaran_jenis_admin_all                 = $total_anggaran_jenis_pegawai_admin_all +
            $total_anggaran_jenis_barjas_admin_all +
            $total_anggaran_jenis_subsidi_admin_all +
            $total_anggaran_jenis_bunga_admin_all +
            $total_anggaran_jenis_modal_tanah_admin_all +
            $total_anggaran_jenis_modal_gedung_admin_all +
            $total_anggaran_jenis_modal_mesin_admin_all;

        $belanja_operasi_all                            = $total_anggaran_jenis_pegawai_admin_all +  $total_anggaran_jenis_subsidi_admin_all + $total_anggaran_jenis_barjas_admin_all + $total_anggaran_jenis_bunga_admin_all;
        $belanja_modal_ringkasan_all                    = $total_anggaran_jenis_modal_tanah_admin_all + $total_anggaran_jenis_modal_mesin_admin_all + $total_anggaran_jenis_modal_gedung_admin_all;
        $belanja_total_all                              = $belanja_operasi_all + $belanja_modal_ringkasan_all;

        /////////////////////////////////////// APBD ///////////////////////////////////////////////////////////
        $pegawai_apbd = ('9cf6025b-eb56-0000-0000-17e74056b7f5');

        $barjas_apbd = [
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e'
        ];

        $modal_apbd = [
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
            '9da0eda4-1669-47b2-aea6-a7f5ea1a79de',
            '9da0edde-1da7-4ea2-9932-5561c75a4c67',
            '9da0ee14-3182-427a-aded-ca2d74729850',
            '9da0ee44-f06e-4aa6-b6d8-6161dbb78b6e',
            '9da0ee72-0697-4631-88c3-9ee16eb1ba08',

            '9da0eea5-26e4-4ebb-8c55-579c8f9392bc',
            '9da0eeca-269d-4b18-95f1-d48b7b246f22',
            '9df8b9ab-6868-4f2f-9f92-986786d8dc92',
            '9e2dab58-8ce3-4d07-8404-4e4c7bbd568a'
        ];

        $aktivitasmodal = ['9cf5e38f-a302-45eb-a1b8-8d0d0e4e5e0f'];

        // Query function to handle both single ID and array of IDs for APBD calculations
        $query_apbd = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('pejabats.id_pejabat', $id_pejabat)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        // Calculate total budgets per category
        $total_anggaran_pegawai_kabag_apbd = $query_apbd($pegawai_apbd);
        $total_anggaran_modal_kabag_apbd = $query_apbd($modal_apbd);
        $total_anggaran_barjas_kabag_apbd = $query_apbd($barjas_apbd);
        $total_anggaran_kabag_apbd = $total_anggaran_pegawai_kabag_apbd + $total_anggaran_modal_kabag_apbd + $total_anggaran_barjas_kabag_apbd;

        // Additional query function for KSP
        $query_ksp_apbd = function ($id_kode_rekening_belanja) use ($id_pejabat, $tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->where('ksps.id_ksp', session('id_ksp'))
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        // Calculate total budgets for KSP
        $total_anggaran_pegawai_ksp_apbd = $query_ksp_apbd($pegawai_apbd);
        $total_anggaran_modal_ksp_apbd = $query_ksp_apbd($modal_apbd);
        $total_anggaran_barjas_ksp_apbd = $query_ksp_apbd($barjas_apbd);
        $total_anggaran_ksp_apbd = $total_anggaran_pegawai_ksp_apbd + $total_anggaran_modal_ksp_apbd + $total_anggaran_barjas_ksp_apbd;

        // Query for user totals
        $total_anggaran_user_apbd = Rkbu::join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
            ->where('id_user', $id_user)
            ->sum('total_anggaran');

        $total_anggaran_modal_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereIn('id_kode_rekening_belanja', $modal_apbd)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_barjas_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereIn('id_kode_rekening_belanja', $barjas_apbd)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        $total_anggaran_pegawai_user_apbd = Rkbu::where('id_user', $id_user)
            ->whereNull('id_kode_rekening_belanja')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->sum('total_anggaran');

        // Query for admin totals
        $query_admin_apbd = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');

            if (is_array($id_kode_rekening_belanja) && !empty($id_kode_rekening_belanja)) {
                $query->whereIn('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            } elseif (!empty($id_kode_rekening_belanja)) {
                $query->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja);
            }

            return $query->sum('total_anggaran');
        };

        $total_anggaran_pegawai_admin_apbd = $query_admin_apbd($pegawai_apbd);
        $total_anggaran_modal_admin_apbd = $query_admin_apbd($modal_apbd);
        $total_anggaran_barjas_admin_apbd = $query_admin_apbd($barjas_apbd);
        $total_anggaran_admin_apbd = $total_anggaran_pegawai_admin_apbd + $total_anggaran_modal_admin_apbd + $total_anggaran_barjas_admin_apbd;

        // Pagu calculation and differences
        $query_pagu_apbd = function ($id_kode_rekening_belanja) use ($tahunAnggaran) {
            return Anggaran::where('tahun_anggaran', $tahunAnggaran)
                ->whereIn('id_kode_rekening_belanja', (array)$id_kode_rekening_belanja)
                ->sum('jumlah_anggaran');
        };

        $pagu_pegawai_apbd = $query_pagu_apbd($pegawai_apbd);
        $pagu_modal_apbd = $query_pagu_apbd($modal_apbd);
        $pagu_barjas_apbd = $query_pagu_apbd($barjas_apbd);
        $total_pagu_apbd = $pagu_pegawai_apbd + $pagu_modal_apbd + $pagu_barjas_apbd;

        $selisih_pagu_apbd = $total_pagu_apbd - $total_anggaran_admin_apbd;
        $selisih_pagu_pegawai_apbd = $pagu_pegawai_apbd - $total_anggaran_pegawai_admin_apbd;
        $selisih_pagu_barjas_apbd = $pagu_barjas_apbd - $total_anggaran_barjas_admin_apbd;
        $selisih_pagu_modal_apbd = $pagu_modal_apbd - $total_anggaran_modal_admin_apbd;

        $total_apbd_blud            = $total_anggaran_admin_apbd + $total_anggaran_admin;
        $total_apbd_blud_pegawai    = $total_anggaran_pegawai_admin + $total_anggaran_pegawai_admin_apbd;
        $total_apbd_blud_barjas     = $total_anggaran_barjas_admin + $total_anggaran_barjas_admin_apbd;
        $total_apbd_blud_modal      = $total_anggaran_modal_admin + $total_anggaran_modal_admin_apbd;

        $total_unit = DB::table('rkbus')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user') // Relasi rkbus -> users
            ->join('units', 'users.id_unit', '=', 'units.id_unit') // Relasi users -> units
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->select(
                'units.nama_unit',
                DB::raw('SUM(rkbus.total_anggaran) as total_anggaran_terinput'),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_perlu_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1edc-2263-401f-b249-361db4017932' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi = '9cfb1f45-ca37-4bd3-8dc9-514c6b9f436c' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_ksp_ditolak"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_perlu_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_validasi"),
                DB::raw("SUM(CASE WHEN rkbus.id_status_validasi_rka = '9cfb1fa1-d0de-4e99-a368-8c218deda960' THEN rkbus.total_anggaran ELSE 0 END) as anggaran_kabag_ditolak")
            )
            ->groupBy('units.nama_unit')
            ->get();

        $programs = Program::whereHas('kegiatans.sub_kegiatans.aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
            // Ambil hanya data RKBU yang tersedia dan sesuai tahun anggaran
            $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');
        })
            ->with([
                'kegiatans' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('sub_kegiatans.aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('aktivitass.rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('rekening_belanja.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass.rekening_belanja' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran)
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'kegiatans.sub_kegiatans.aktivitass.rekening_belanja.rkbus'
            ])
            ->get();


        // return response()->json($programs);
        return view('dashboard.dashboard_apbd', compact(
            'total_anggaran_user',
            'total_unit',
            'programs',
            'total_anggaran_ksp',
            'total_anggaran_kabag',
            'total_anggaran_admin',
            'total_anggaran_modal_user',
            'total_anggaran_barjas_user',
            'total_anggaran_pegawai_user',
            'total_anggaran_modal_ksp',
            'total_anggaran_barjas_ksp',
            'total_anggaran_pegawai_ksp',
            'total_anggaran_modal_kabag',
            'total_anggaran_barjas_kabag',
            'total_anggaran_pegawai_kabag',
            'total_anggaran_modal_admin',
            'total_anggaran_barjas_admin',
            'total_anggaran_pegawai_admin',

            'total_anggaran_pegawai_admin_all',
            'total_anggaran_modal_admin_all',
            'total_anggaran_barjas_admin_all',
            'total_anggaran_admin_all',
            'total_anggaran_jenis_pegawai_admin_all',
            'total_anggaran_jenis_barjas_admin_all',
            'total_anggaran_jenis_subsidi_admin_all',
            'total_anggaran_jenis_bunga_admin_all',
            'total_anggaran_jenis_modal_tanah_admin_all',
            'total_anggaran_jenis_modal_mesin_admin_all',
            'total_anggaran_jenis_modal_gedung_admin_all',
            'total_anggaran_jenis_admin_all',
            'belanja_operasi_all',
            'belanja_modal_ringkasan_all',
            'belanja_total_all',

            'total_anggaran_user_apbd',
            'total_anggaran_ksp_apbd',
            'total_anggaran_kabag_apbd',
            'total_anggaran_admin_apbd',
            'total_anggaran_modal_user_apbd',
            'total_anggaran_barjas_user_apbd',
            'total_anggaran_pegawai_user_apbd',
            'total_anggaran_modal_ksp_apbd',
            'total_anggaran_barjas_ksp_apbd',
            'total_anggaran_pegawai_ksp_apbd',
            'total_anggaran_modal_kabag_apbd',
            'total_anggaran_barjas_kabag_apbd',
            'total_anggaran_pegawai_kabag_apbd',
            'total_anggaran_modal_admin_apbd',
            'total_anggaran_barjas_admin_apbd',
            'total_anggaran_pegawai_admin_apbd',
            'pagu_pegawai',
            'pagu_barjas',
            'pagu_modal',
            'total_pagu',
            'selisih_pagu',
            'selisih_pagu_pegawai',
            'selisih_pagu_barjas',
            'selisih_pagu_modal',
            'total_anggaran_jenis_pegawai_admin',
            'total_anggaran_jenis_barjas_admin',
            'total_anggaran_jenis_bunga_admin',
            'total_anggaran_jenis_modal_tanah_admin',
            'total_anggaran_jenis_modal_mesin_admin',
            'total_anggaran_jenis_modal_gedung_admin',
            'total_anggaran_jenis_admin',
            'belanja_operasi',
            'belanja_modal_ringkasan',
            'total_anggaran_operasi_ksp',
            'total_apbd_blud',
            'total_apbd_blud_pegawai',
            'total_apbd_blud_barjas',
            'total_apbd_blud_modal',

        ));
    }

    public function realisasi()
    {
        // Data Asset
        return view('dashboard.dashboard_realisasi');
    }
}
