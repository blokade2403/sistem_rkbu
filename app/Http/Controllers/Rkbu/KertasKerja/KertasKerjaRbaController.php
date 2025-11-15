<?php

namespace App\Http\Controllers\Rkbu\KertasKerja;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class KertasKerjaRbaController extends Controller
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


        // Ambil data rkbu berdasarkan filter sub_kategori_rkbu
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
        // $total_anggaran = $total_anggaran_per_jenis_kategori->sum('total_anggaran');
        // // Tampilkan hasil untuk masing-masing id_jenis_kategori_rkbu
        // dd($total_anggaran_per_jenis_kategori);

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


        // Ambil hierarki berdasarkan rkbus yang tersedia
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


        // Proses perhitungan total anggaran tetap sama

        //$total_anggaran_blud = 0;
        $total_anggaran_jenis = 0;
        $total_anggaran_kategori = 0;
        $total_anggaran_kategori_rkbu = 0;

        // Perhitungan total anggaran tetap sama
        foreach ($categories as $jenisBelanja) {
            $total_anggaran_jenis = 0;

            foreach ($jenisBelanja->jenis_kategori_rkbus as $jenisKategori) {
                $total_anggaran_kategori = 0;

                foreach ($jenisKategori->obyek_belanjas as $obyekBelanja) {
                    foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu) {
                        $total_anggaran_kategori_rkbu = 0;

                        foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategoriRkbu) {
                            // Perhitungan total anggaran untuk sub_kategori_rkbu
                            $total_anggaran_sub_kategori = $subKategoriRkbu->rkbus->sum('total_anggaran');

                            // Simpan total anggaran ke dalam properti subKategoriRkbu
                            $subKategoriRkbu->total_anggaran = $total_anggaran_sub_kategori;

                            // Tambahkan total anggaran sub_kategori_rkbu ke total anggaran kategori_rkbu
                            $total_anggaran_kategori_rkbu += $total_anggaran_sub_kategori;
                        }

                        // Simpan total anggaran kategori_rkbu
                        $kategoriRkbu->total_anggaran = $total_anggaran_kategori_rkbu;

                        // Tambahkan total anggaran kategori_rkbu ke total anggaran kategori
                        $total_anggaran_kategori += $total_anggaran_kategori_rkbu;
                    }
                }

                // Simpan total anggaran kategori
                $jenisKategori->total_anggaran = $total_anggaran_kategori;

                // Tambahkan total anggaran kategori ke total anggaran jenis belanja
                $total_anggaran_jenis += $total_anggaran_kategori;
            }

            // Simpan total anggaran jenis belanja
            $jenisBelanja->total_anggaran = $total_anggaran_jenis;
        }

        $logo = JudulHeader::get();
        // Cek apakah data kosong
        $dataKosong = $categories->isEmpty();

        return view('backend.ringkasan.kertas_kerja.index', compact('rkbus', 'dataKosong', 'logo', 'sisa_anggaran_blud', 'total_anggaran_blud', 'sub_kategori_rkbus', 'status_validasi_rka', 'categories', 'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan', 'total_anggaran_jenis', 'total_anggaran_kategori', 'total_anggaran_kategori_rkbu', 'total_anggaran_kategori_rkbu'));
    }
}
