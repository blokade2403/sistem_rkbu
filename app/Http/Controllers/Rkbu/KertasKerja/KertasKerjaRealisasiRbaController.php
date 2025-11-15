<?php

namespace App\Http\Controllers\Rkbu\KertasKerja;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class KertasKerjaRealisasiRbaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil id_user dari session
        // dd(session('id_user'), session('tahun_anggaran'));
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::whereHas('rkbus', function ($query) {
            $query->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                $query->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');
            });
        })->get();

        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait


        // Query dengan relasi dan filter yang dibutuhkan
        $query = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_dana',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu',
        ])
            ->where('nama_tahun_anggaran', session('tahun_anggaran'))
            ->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                // Filter berdasarkan id_jenis_kategori_rkbu
                $query->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');
            });

        // dd($query);

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->has('id_status_validasi_rka') && $request->id_status_validasi_rka != '') {
            $query->where('id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        $rkbus = Rkbu::with([
            'subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu'
        ])
            ->where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->get();


        // Ambil semua id_jenis_kategori_rkbu
        $id_jenis_kategori_rkbu = $rkbus->pluck('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu.id_jenis_kategori_rkbu')
            ->unique();

        $id_jenis_belanja = $rkbus->pluck('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu.jenis_belanja.id_jenis_belanja')
            ->unique();


        // Pastikan $rkbus ada dan tidak kosong
        if ($rkbus->isNotEmpty()) {
            // Ambil data program, kegiatan, dan sub kegiatan dengan pengecekan null yang lebih aman
            $firstRkbu = $rkbus->first();

            $namaProgram = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan->kegiatan->program)->nama_program ?? 'Nama Program Tidak Ada';
            $kodeProgram = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan->kegiatan->program)->kode_program ?? 'Kode Program Tidak Ada';

            $namaKegiatan = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan->kegiatan)->nama_kegiatan ?? 'Nama Kegiatan Tidak Ada';
            $kodeKegiatan = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan->kegiatan)->kode_kegiatan ?? 'Kode Kegiatan Tidak Ada';

            $namaSubKegiatan = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan)->nama_sub_kegiatan ?? 'Nama Sub Kegiatan Tidak Ada';
            $kodeSubKegiatan = optional($firstRkbu->rekening_belanjas->first()->aktivitas->sub_kegiatan)->kode_sub_kegiatan ?? 'Kode Sub Kegiatan Tidak Ada';
        } else {
            // Jika tidak ada data di $rkbus, berikan nilai default
            $namaProgram = 'Nama Program Tidak Ada';
            $kodeProgram = 'Kode Program Tidak Ada';
            $namaKegiatan = 'Nama Kegiatan Tidak Ada';
            $kodeKegiatan = 'Kode Kegiatan Tidak Ada';
            $namaSubKegiatan = 'Nama Sub Kegiatan Tidak Ada';
            $kodeSubKegiatan = 'Kode Sub Kegiatan Tidak Ada';
        }

        $tahunAnggaran = Session::get('tahun_anggaran');
        // Ambil id_jenis_kategori_rkbu dari data yang didapatkan

        // Pastikan bahwa $id_jenis_kategori_rkbu tidak kosong
        if (!$id_jenis_kategori_rkbu) {
            return redirect()->back()->with('error', 'Data id_jenis_kategori_rkbu tidak ditemukan.');
        }

        $categories = JenisBelanja::whereHas('jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus.rekening_belanjas.aktivitas', function ($query) use ($tahunAnggaran) {
            // Filter berdasarkan id_sumber_dana
            $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran);
            // ->where('id_sumber_dana', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3');
        })
            ->with([
                'jenis_kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('kategori_rkbus.sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    $query->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus' => function ($query) use ($tahunAnggaran) {
                    $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                        ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                        ->whereIn('rkbus.id_kode_rekening_belanja', [
                            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
                            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
                            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
                        ]);
                }
            ])
            ->get();

        $total_anggaran_per_jenis_kategori = DB::table('rkbus')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->select('jenis_kategori_rkbus.id_jenis_kategori_rkbu', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran'))
            ->groupBy('jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->get();

        $total_anggaran_per_jenis_belanja = DB::table('rkbus')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('jenis_belanjas', 'jenis_kategori_rkbus.id_jenis_belanja', '=', 'jenis_belanjas.id_jenis_belanja')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->select('jenis_belanjas.id_jenis_belanja', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran'))
            ->groupBy('jenis_belanjas.id_jenis_belanja')
            ->get();

        $total_anggaran_blud = DB::table('rkbus')
            ->where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->whereIn('id_kode_rekening_belanja', [
                '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
                '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
                '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            ])
            ->sum('total_anggaran');

        $sisa_anggaran_blud = DB::table('rkbus')
            ->where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->whereIn('id_kode_rekening_belanja', [
                '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
                '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
                '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            ])
            ->sum('sisa_anggaran_rkbu');

        // Tampilkan hasil
        // dd($total_anggaran_blud);

        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        $total_anggaran_pluck = DB::table('rkbus')
            ->select('sub_kategori_rkbus.id_sub_kategori_rkbu', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran'))
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->groupBy('sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->pluck('total_anggaran', 'id_sub_kategori_rkbu', 'nama_sub_kategori_rkbu');

        $total_anggaran_per_jenis_kategori_pluck = DB::table('rkbus')
            ->select('jenis_kategori_rkbus.id_jenis_kategori_rkbu', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran_jenis_kategori'))
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->groupBy('jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->pluck('total_anggaran_jenis_kategori', 'id_jenis_kategori_rkbu', 'nama_jenis_kategori_rkbu');

        $total_anggaran_per_jenis_belanja_pluck = DB::table('rkbus')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('jenis_belanjas', 'jenis_kategori_rkbus.id_jenis_belanja', '=', 'jenis_belanjas.id_jenis_belanja')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->select('jenis_belanjas.id_jenis_belanja', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran_jenis_belanja'))
            ->groupBy('jenis_belanjas.id_jenis_belanja')
            ->pluck('total_anggaran_jenis_belanja', 'id_jenis_belanja', 'nama_jenis_belanja');

        $total_anggaran_kategori = DB::table('rkbus')
            ->select('kategori_rkbus.id_kategori_rkbu', DB::raw('SUM(rkbus.total_anggaran) as total_anggaran_kategori'))
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') // Validasi status RKA
            ->groupBy('kategori_rkbus.id_kategori_rkbu')
            ->pluck('total_anggaran_kategori', 'id_kategori_rkbu');

        $realisasi_anggaran_per_jenis_kategori = DB::table('master_spjs')
            ->select('jenis_kategori_rkbus.id_jenis_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.pembayaran) as total_realisasi_jenis_kategori'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('master_spjs.status_pembayaran', 'Sudah di Bayar')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->pluck('total_realisasi_jenis_kategori', 'id_jenis_kategori_rkbu');

        $realisasi_anggaran_per_jenis_belanja = DB::table('master_spjs')
            ->select('jenis_belanjas.id_jenis_belanja', DB::raw('SUM(DISTINCT master_spjs.pembayaran) as total_realisasi_jenis_belanja'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('jenis_belanjas', 'jenis_kategori_rkbus.id_jenis_belanja', '=', 'jenis_belanjas.id_jenis_belanja')
            ->where('master_spjs.status_pembayaran', 'Sudah di Bayar')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('jenis_belanjas.id_jenis_belanja')
            ->pluck('total_realisasi_jenis_belanja', 'id_jenis_belanja');

        $logo = JudulHeader::get();

        $realisasi_per_kategori = DB::table('master_spjs')
            ->select('kategori_rkbus.id_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.pembayaran) as total_realisasi_kategori'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->where('master_spjs.status_pembayaran', 'Sudah di Bayar')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('kategori_rkbus.id_kategori_rkbu')
            ->pluck('total_realisasi_kategori', 'id_kategori_rkbu');

        $realisasi_per_sub_kategori = DB::table('master_spjs')
            ->select('sub_kategori_rkbus.id_sub_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.pembayaran) as total_pembayaran'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->where('master_spjs.status_pembayaran', 'Sudah di Bayar')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->pluck('total_pembayaran', 'sub_kategori_rkbus.id_sub_kategori_rkbu');

        $proses_spj_anggaran_per_jenis_kategori = DB::table('master_spjs')
            ->select('jenis_kategori_rkbus.id_jenis_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.bruto) as total_proses_spj_jenis_kategori'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('master_spjs.ket_verif_bendahara', 'Proses')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->pluck('total_proses_spj_jenis_kategori', 'id_jenis_kategori_rkbu');

        $proses_spj_anggaran_per_jenis_belanja = DB::table('master_spjs')
            ->select('jenis_belanjas.id_jenis_belanja', DB::raw('SUM(DISTINCT master_spjs.bruto) as total_proses_spj_jenis_belanja'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('obyek_belanjas', 'kategori_rkbus.id_obyek_belanja', '=', 'obyek_belanjas.id_obyek_belanja')
            ->join('jenis_kategori_rkbus', 'obyek_belanjas.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('jenis_belanjas', 'jenis_kategori_rkbus.id_jenis_belanja', '=', 'jenis_belanjas.id_jenis_belanja')
            ->where('master_spjs.ket_verif_bendahara', 'Proses')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('jenis_belanjas.id_jenis_belanja')
            ->pluck('total_proses_spj_jenis_belanja', 'id_jenis_belanja');

        $proses_spj_per_kategori = DB::table('master_spjs')
            ->select('kategori_rkbus.id_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.bruto) as total_proses_spj_kategori'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->where('master_spjs.ket_verif_bendahara', 'Proses')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('kategori_rkbus.id_kategori_rkbu')
            ->pluck('total_proses_spj_kategori', 'id_kategori_rkbu');

        $proses_spj_per_sub_kategori = DB::table('master_spjs')
            ->select('sub_kategori_rkbus.id_sub_kategori_rkbu', DB::raw('SUM(DISTINCT master_spjs.bruto) as total_proses_spj'))
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->where('master_spjs.status_proses_pesanan', 'Selesai')
            ->where('master_spjs.status_pembayaran', null)
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->groupBy('sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->pluck('total_proses_spj', 'sub_kategori_rkbus.id_sub_kategori_rkbu');

        $master_realisasi = DB::table('master_spjs')
            ->select(
                'sub_kategori_rkbus.id_sub_kategori_rkbu',
                'master_spjs.bulan_pembayaran',
                'master_spjs.pembayaran'
            )
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->where('master_spjs.ket_verif_bendahara', 'Sudah dibayar')
            ->get();

        $master_proses = DB::table('master_spjs')
            ->select(
                'sub_kategori_rkbus.id_sub_kategori_rkbu',
                DB::raw('MONTH(master_spjs.tgl_surat_pesanan) as bulan2'),
                'master_spjs.bruto'
            )
            ->join('spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj')
            ->join('spj_details', 'spjs.id_spj', '=', 'spj_details.id_spj')
            ->join('usulan_barangs', 'spj_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->where('master_spjs.ket_verif_bendahara', 'Proses')
            ->get();

        $realisasi_per_bulan_sub_kategori = [];

        foreach ($master_realisasi as $spj) {
            $id_sub = $spj->id_sub_kategori_rkbu;
            $bulan = $spj->bulan_pembayaran; // Sudah langsung 'Mei' dari DB
            $nilai = $spj->pembayaran;

            if (!isset($realisasi_per_bulan_sub_kategori[$id_sub])) {
                $realisasi_per_bulan_sub_kategori[$id_sub] = [];
            }

            $realisasi_per_bulan_sub_kategori[$id_sub][$bulan] =
                ($realisasi_per_bulan_sub_kategori[$id_sub][$bulan] ?? 0) + $nilai;
        }

        $proses_per_bulan_sub_kategori = [];

        foreach ($master_proses as $spj) {
            $id_sub = $spj->id_sub_kategori_rkbu;
            $bulan = $spj->bulan2; // Angka bulan (5, 6, dll.)
            $nilai = $spj->bruto;

            // Opsional: Convert angka bulan ke nama bulan
            $bulanNama = Carbon::createFromDate(null, $bulan)->translatedFormat('F');

            if (!isset($proses_per_bulan_sub_kategori[$id_sub])) {
                $proses_per_bulan_sub_kategori[$id_sub] = [];
            }

            $proses_per_bulan_sub_kategori[$id_sub][$bulanNama] =
                ($proses_per_bulan_sub_kategori[$id_sub][$bulanNama] ?? 0) + $nilai;
        }

        // return response()->json([
        //     'realisasi' => $realisasi_per_bulan_sub_kategori,
        //     'proses_spj' => $proses_per_bulan_sub_kategori,
        // ]);

        $jenis_belanja_data = [];

        foreach ($categories as $jenisBelanja) {
            $id_jenis_belanja = $jenisBelanja->id_jenis_belanja;
            $jenis_kategori_items = [];
            $total_realisasi_jenis_belanja = 0;
            $total_proses_spj_jenis_belanja = 0;

            foreach ($jenisBelanja->jenis_kategori_rkbus as $jenisKategori) {
                $id_jenis_kategori = $jenisKategori->id_jenis_kategori_rkbu;
                $obyek_belanja_items = [];
                $total_realisasi_jenis_kategori = 0;
                $total_proses_spj_jenis_kategori = 0;

                foreach ($jenisKategori->obyek_belanjas as $obyekBelanja) {
                    $kategori_items = [];

                    foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu) {
                        $id_kategori = $kategoriRkbu->id_kategori_rkbu;
                        $sub_kategori_items = [];
                        $total_realisasi_kategori = 0;
                        $total_proses_kategori = 0;

                        foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategoriRkbu) {
                            $id_sub = $subKategoriRkbu->id_sub_kategori_rkbu;

                            // Tambahkan per bulan
                            $realisasi_per_bulan = [];
                            for ($bulan = 1; $bulan <= 12; $bulan++) {
                                $realisasi_per_bulan["bulan_$bulan"] = $realisasi_per_bulan_sub_kategori[$id_sub][$bulan] ?? 0;
                            }

                            $subKategoriRkbu->total_anggaran = $total_anggaran_pluck[$id_sub] ?? 0;
                            $subKategoriRkbu->realisasi_anggaran = $realisasi_per_sub_kategori[$id_sub] ?? 0;
                            $subKategoriRkbu->proses_anggaran = $proses_spj_per_sub_kategori[$id_sub] ?? 0;
                            $subKategoriRkbu->proses_klik = $proses_aklik_spj_per_sub_kategori[$id_sub] ?? 0;

                            $total_realisasi_kategori   += $subKategoriRkbu->realisasi_anggaran;
                            $total_proses_kategori      += $subKategoriRkbu->proses_anggaran;

                            $sub_kategori_items[] = array_merge([
                                'id_sub_kategori_rkbu' => $id_sub,
                                'nama_sub_kategori_rkbu' => $subKategoriRkbu->nama_sub_kategori_rkbu,
                                'total_anggaran' => $subKategoriRkbu->total_anggaran,
                                'realisasi_anggaran' => $subKategoriRkbu->realisasi_anggaran,
                                'proses_anggaran' => $subKategoriRkbu->proses_anggaran,
                            ], $realisasi_per_bulan);
                        }

                        $kategoriRkbu->total_anggaran = $total_anggaran_kategori[$id_kategori] ?? 0;
                        $kategoriRkbu->realisasi_anggaran = $total_realisasi_kategori;
                        $kategoriRkbu->proses_anggaran = $total_proses_kategori;

                        $kategori_items[] = [
                            'id_kategori_rkbu' => $id_kategori,
                            'nama_kategori_rkbu' => $kategoriRkbu->nama_kategori_rkbu,
                            'total_anggaran' => $kategoriRkbu->total_anggaran,
                            'realisasi_anggaran' => $kategoriRkbu->realisasi_anggaran,
                            'proses_anggaran' => $kategoriRkbu->proses_anggaran,
                            'sub_kategori' => $sub_kategori_items,
                        ];

                        $total_realisasi_jenis_kategori += $kategoriRkbu->realisasi_anggaran;
                        $total_proses_spj_jenis_kategori += $kategoriRkbu->proses_anggaran;
                    }

                    $obyek_belanja_items[] = $kategori_items;
                }

                $jenisKategori->total_anggaran = $total_anggaran_per_jenis_kategori_pluck[$id_jenis_kategori] ?? 0;
                $jenisKategori->realisasi_anggaran = $total_realisasi_jenis_kategori;
                $jenisKategori->proses_anggaran = $total_proses_spj_jenis_kategori;

                $jenis_kategori_items[] = [
                    'id_jenis_kategori_rkbu' => $id_jenis_kategori,
                    'nama_jenis_kategori_rkbu' => $jenisKategori->nama_jenis_kategori_rkbu,
                    'total_anggaran' => $jenisKategori->total_anggaran,
                    'realisasi_anggaran' => $jenisKategori->realisasi_anggaran,
                    'proses_anggaran' => $jenisKategori->proses_anggaran,
                    'obyek_belanja' => $obyek_belanja_items,
                ];

                $total_realisasi_jenis_belanja += $jenisKategori->realisasi_anggaran;
                $total_proses_spj_jenis_belanja += $jenisKategori->proses_anggaran;
            }

            $jenisBelanja->total_anggaran = $total_anggaran_per_jenis_belanja_pluck[$id_jenis_belanja] ?? 0;
            $jenisBelanja->realisasi_anggaran = $total_realisasi_jenis_belanja;
            $jenisBelanja->proses_anggaran = $total_proses_spj_jenis_belanja;

            $jenis_belanja_data[] = [
                'id_jenis_belanja' => $id_jenis_belanja,
                'nama_jenis_belanja' => $jenisBelanja->nama_jenis_belanja,
                'total_anggaran' => $jenisBelanja->total_anggaran,
                'realisasi_anggaran' => $jenisBelanja->realisasi_anggaran,
                'proses_anggaran' => $jenisBelanja->proses_anggaran,
                'jenis_kategori' => $jenis_kategori_items,
            ];
        }

        $bulanList = collect([
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ]);

        // Ambil data realisasi dari master_spjs
        $realisasi = DB::table('master_spjs')
            ->selectRaw('MONTH(tanggal_pembayaran) as bulan_ke, SUM(pembayaran) as total')
            ->whereNotNull('tanggal_pembayaran')
            ->whereNotNull('pembayaran')
            ->groupBy('bulan_ke')
            ->pluck('total', 'bulan_ke'); // [7 => 33645210, 1 => 1000000, dst]

        // Ambil data target dari target_spjs
        $target = DB::table('target_sps')
            ->selectRaw('bulan, target')
            ->pluck('target', 'bulan'); // [1 => 50000000, 2 => 50000000, dst]

        $dataGabungan = $bulanList->map(function ($nama, $index) use ($realisasi, $target) {
            $bulanKe = $index + 1;
            return [
                'bulan'     => $nama,
                'realisasi' => (int) ($realisasi[$bulanKe] ?? 0),
                'target'    => (int) ($target[$nama] ?? 0),
            ];
        });

        // dd($jenis_belanja_data);

        $total_anggaran_jenis = 0;
        $total_anggaran_kategori = 0;
        $total_anggaran_kategori_rkbu = 0;
        $total_realisasi_anggaran_kategori_rkbu = 0;
        $total_proses_spj_anggaran_kategori_rkbu = 0;

        foreach ($categories as $jenisBelanja) {
            $total_anggaran_jenis = 0;

            foreach ($jenisBelanja->jenis_kategori_rkbus as $jenisKategori) {
                $total_anggaran_kategori = 0;

                foreach ($jenisKategori->obyek_belanjas as $obyekBelanja) {
                    foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu) {
                        $total_anggaran_kategori_rkbu = 0;
                        $total_realisasi_anggaran_kategori_rkbu = 0;
                        $total_proses_spj_anggaran_kategori_rkbu = 0;

                        foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategoriRkbu) {
                            $total_anggaran_sub_kategori = $subKategoriRkbu->rkbus->sum('total_anggaran');
                            $subKategoriRkbu->total_anggaran = $total_anggaran_sub_kategori;
                            $total_anggaran_kategori_rkbu += $total_anggaran_sub_kategori;
                        }
                        $kategoriRkbu->total_anggaran = $total_anggaran_kategori_rkbu;
                        $total_anggaran_kategori += $total_anggaran_kategori_rkbu;
                    }
                }
                $jenisKategori->total_anggaran = $total_anggaran_kategori;
                $total_anggaran_jenis += $total_anggaran_kategori;
            }
            $jenisBelanja->total_anggaran = $total_anggaran_jenis;
        }

        $total_anggaran_blud = 0;
        $total_realisasi_blud = 0;
        $total_proses_spj_blud = 0;

        foreach ($categories as $jenisBelanja) {
            $total_anggaran_blud += $jenisBelanja->total_anggaran;
            $total_realisasi_blud += $jenisBelanja->realisasi_anggaran;
            $total_proses_spj_blud += $jenisBelanja->proses_anggaran;
        }

        $total_sisa_anggaran_blud = $total_anggaran_blud - ($total_realisasi_blud + $total_proses_spj_blud);
        // dd($realisasi_per_sub_kategori, $proses_spj_per_sub_kategori, $total_anggaran_kategori_rkbu, $total_realisasi_anggaran_kategori_rkbu);

        // Cek apakah data kosong
        $dataKosong = $categories->isEmpty();


        return view('rkbu.kertas_kerja.realisasi_rba', compact('rkbus', 'dataGabungan', 'logo', 'dataKosong', 'sisa_anggaran_blud', 'total_realisasi_blud', 'total_proses_spj_blud', 'total_anggaran_blud', 'sub_kategori_rkbus', 'status_validasi_rka', 'categories', 'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan', 'total_anggaran_jenis', 'total_anggaran_kategori', 'total_anggaran_kategori_rkbu', 'total_anggaran_kategori_rkbu'));
    }
}
