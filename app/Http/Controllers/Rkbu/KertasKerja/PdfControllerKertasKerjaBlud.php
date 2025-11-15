<?php

namespace App\Http\Controllers\Rkbu\KertasKerja;

use App\Pdf\MYPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class PdfControllerKertasKerjaBlud extends Controller
{
    public function downloadPDF(Request $request)
    {
        // Ambil id_user dari session
        // dd(session('id_user'), session('tahun_anggaran'));
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $id_tahun_anggaran   = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('id');
        $id_fase = session('id_fase');

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::whereHas('rkbus', function ($query) {
            $query->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                $query->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');
            });
        })->get();

        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait

        $tanggal_perencanaans = DB::table('tanggal_perencanaans')
            ->join('tahun_anggarans', 'tanggal_perencanaans.id_tahun_anggaran', '=', 'tahun_anggarans.id')
            ->where('tanggal_perencanaans.id_tahun_anggaran', $id_tahun_anggaran)
            ->where('tanggal_perencanaans.id_fase', $id_fase)
            ->select('tanggal_perencanaans.tanggal')
            ->get()
            ->map(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y');
            })
            ->toArray();


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

        $query_dev = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->join('jabatans', 'pejabats.id_jabatan', '=', 'jabatans.id_jabatan')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            // ->where('pejabats.id_pejabat', $id_pejabat)
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->whereIn('rkbus.id_kode_rekening_belanja', [
                '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
                '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
                '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
            ])
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('sumber_danas.id_sumber_dana', '!=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1');

        $judulHeaders = JudulHeader::first();
        $first_pejabat  = Pejabat::join('jabatans', 'jabatans.id_jabatan', '=', 'pejabats.id_jabatan')
            ->where('jabatans.nama_jabatan', 'Kepala Bagian Administrasi Umum dan Keuangan')
            ->select('jabatans.*', 'pejabats.nama_pejabat', 'pejabats.nip_pejabat') // Pilih kolom yang ingin diambil
            ->first();

        $first_direktur = Pejabat::join('jabatans', 'jabatans.id_jabatan', '=', 'pejabats.id_jabatan')
            ->where('jabatans.nama_jabatan', 'Direktur')
            ->select('jabatans.*', 'pejabats.nama_pejabat', 'pejabats.nip_pejabat') // Pilih kolom yang ingin diambil
            ->first();

        // Inisialisasi variabel dengan nilai default
        $jabatan_kabag      = '...............';
        $nama_pejabat       = '...............';
        $nip_pejabat_kabag  = '...............';
        $nama_direktur      = '...............';
        $nip_direktur       = '...............';

        // dd($first_pejabat->nip_pejabat);

        if ($first_pejabat) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_pejabat       = $first_pejabat->nama_pejabat ?? '...............';
            $nip_pejabat_kabag  = $first_pejabat->nip_pejabat ?? '...............';
            $jabatan_kabag      = $first_pejabat->nama_jabatan ?? '...............';
        }

        if ($first_direktur) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_direktur      = $first_direktur->nama_pejabat ?? '...............';
            $nip_direktur       = $first_direktur->nip_pejabat ?? '...............';
        }

        $nama_rumah_sakit = $judulHeaders->nama_rs;

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

            $namaProgram = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan->kegiatan->program)->nama_program ?? 'Nama Program Tidak Ada';
            $kodeProgram = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan->kegiatan->program)->kode_program ?? 'Kode Program Tidak Ada';

            $namaKegiatan = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan->kegiatan)->nama_kegiatan ?? 'Nama Kegiatan Tidak Ada';
            $kodeKegiatan = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan->kegiatan)->kode_kegiatan ?? 'Kode Kegiatan Tidak Ada';

            $namaSubKegiatan = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan)->nama_sub_kegiatan ?? 'Nama Sub Kegiatan Tidak Ada';
            $kodeSubKegiatan = optional($firstRkbu->rekening_belanjas->aktivitas->sub_kegiatan)->kode_sub_kegiatan ?? 'Kode Sub Kegiatan Tidak Ada';
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

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->has('id_status_validasi_rka') && $request->id_status_validasi_rka != '') {
            $query->where('id_status_validasi_rka', $request->id_status_validasi_rka);
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

        // Tampilkan hasil untuk masing-masing id_jenis_kategori_rkbu
        // dd($total_anggaran_per_jenis_belanja);


        // Ambil hierarki berdasarkan rkbus yang tersedia
        $categories = JenisBelanja::whereHas('jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
            // Filter di level rkbus
            $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran);
        })
            ->with([
                'jenis_kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    // Filter pada jenis_kategori_rkbus berdasarkan rkbus
                    $query->whereHas('obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas' => function ($query) use ($tahunAnggaran) {
                    // Filter pada obyek_belanjas berdasarkan rkbus
                    $query->whereHas('kategori_rkbus.sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    // Filter pada kategori_rkbus berdasarkan rkbus
                    $query->whereHas('sub_kategori_rkbus.rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus' => function ($query) use ($tahunAnggaran) {
                    // Filter pada sub_kategori_rkbus berdasarkan rkbus
                    $query->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                        $query->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus' => function ($query) use ($tahunAnggaran) {
                    // Filter di level rkbus
                    $query->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                        ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                        ->whereIn('rkbus.id_kode_rekening_belanja', [
                            '9cf603bb-bfd0-4b1e-8a24-7339459d9507',
                            '9cf603e2-e748-49f0-949f-6c3c30d42c3e',
                            '9cf6040a-2759-4d16-a3cf-3eee5194a2d5',
                        ]);
                }
            ])
            ->get();

        //   // Hitung total anggaran berdasarkan kategori
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


        // Inisialisasi TCPDF
        $pdf = new MYPDF();

        // Set informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nama Penulis');
        $pdf->SetTitle('Laporan RKBU');
        $pdf->SetSubject('Laporan RKBU');
        $pdf->SetKeywords('TCPDF, PDF, RKBU, example');
        $pdf->SetDisplayMode('real', 'default');

        // Set resolusi halaman
        // $resolusi = array(240, 355);
        // $pdf->SetPageFormat($resolusi, 'P');
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->Cell(0, 10, 'Header', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 7);


        // Mengatur margin halaman dan auto page break
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 30); // Auto page break dengan margin bawah
        $pdf->setCellPaddings(1, 1, 1, 1); // Padding cell

        // Cek data judul headers dari database
        $judulHeaders = JudulHeader::first();

        // Cek dan atur gambar, gunakan gambar default jika tidak tersedia
        $gambar1 = $judulHeaders->gambar1 ? asset('storage/uploads/' . basename($judulHeaders->gambar1)) : asset('storage/uploads/foto.png');
        $gambar2 = $judulHeaders->gambar2 ? asset('storage/uploads/' . basename($judulHeaders->gambar2)) : asset('storage/uploads/foto.png');

        $nama_rumah_sakit = $judulHeaders->nama_rs;
        // Tambahkan halaman pertama
        $pdf->AddPage('P');

        // Buat konten HTML untuk tabel header
        $html = '
                  <table border="0">
                      <tr>
                          <td width="20%" align="left">
                              <img src="' . $gambar2 . '" width="45" height="45" />
                          </td>
                          <td width="60%" align="center">
                              <h3 style="font-size:8px; font-weight: normal;">' . $judulHeaders->header1 . '</h3>
                              <h3 style="font-weight: normal; font-size:8px; line-height:0;">' . $judulHeaders->nama_rs . '</h3>
                              <h3 style="font-size:8px; font-weight: normal;">' . $judulHeaders->header2 . '</h3>
                              <h3 style="font-weight: normal; font-size:8px; line-height:0;">TAHUN ANGGARAN ' . $tahunAnggaran . '</h3>
                          </td>
                          <td width="20%" align="right">
                              <img src="' . $gambar1 . '" width="75" height="45" />
                          </td>
                      </tr>
                  </table>';

        // Tambahkan header ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Cek dan tambahkan halaman baru jika diperlukan sebelum menulis konten tabel
        $requiredSpace = 50; // Tentukan tinggi yang diperlukan untuk elemen (misalnya tabel)
        $this->checkPageBreak($pdf, $requiredSpace);

        // Tambahkan konten body dari view 'print_rkbu_modal_kantor'
        $htmlBody = view('frontend.prints.print_kertas_kerja', compact('rkbus', 'pdf', 'tanggal_perencanaans', 'total_anggaran_blud', 'nama_rumah_sakit', 'nama_pejabat', 'nip_pejabat_kabag', 'nip_direktur', 'nama_direktur', 'sub_kategori_rkbus', 'status_validasi_rka', 'categories', 'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan', 'total_anggaran_jenis', 'total_anggaran_kategori', 'total_anggaran_kategori_rkbu', 'total_anggaran_kategori_rkbu'))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        checkPageBreak($pdf, 50); // 50 untuk memastikan ruang yang cukup untuk tanda tangan
        $htmlSignatureTable = '
            <table rules="none">
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">Jakarta, ' . implode(', ', $tanggal_perencanaans) . '</th>
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">Kepala Program</th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">Direktur ' . ucwords(strtolower($nama_rumah_sakit)) . '</th>
                </tr>
                <tr class="table-rows-he">
                    <th scope="row" width="20%" align="center" style="line-height: 12;"></th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center"></th> 
                </tr> 
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">' . $nama_pejabat . '</th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nama_direktur . '</th>
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center">' . $nip_pejabat_kabag . '</th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nip_direktur . '</th>
                </tr>
            </table>';
        $pdf->writeHTML($htmlSignatureTable, true, false, true, false, '');

        // Tampilkan PDF sebagai preview di browser
        $pdf->Output('rkbu_data_preview.pdf', 'I');


        // Fungsi untuk memeriksa pemecahan halaman sebelum menambah konten baru

    }

    function CheckPageBreak($pdf, $height)
    {
        // Get the current position
        $currentY = $pdf->GetY();

        // Get the height of the page
        $pageHeight = $pdf->getPageHeight();

        // Check if the current Y position + the height of the next row will exceed the page height minus bottom margin
        if ($currentY + $height > $pageHeight - $pdf->getBreakMargin()) {
            // Add a new page if space is insufficient
            $pdf->AddPage();
        }
    }
}
