<?php

namespace App\Http\Controllers\Rkbu\Pegawai;

use App\Pdf\MYPDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class PdfControllerValidasiPegawaiPnsKsp extends Controller
{
    public function downloadPDF(Request $request)
    {
        // Ambil id_user dari session
        // dd(session('id_user'), session('tahun_anggaran'));
        $id_ksp = session('id_ksp');
        $tahunAnggaran = Session::get('tahun_anggaran');

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::whereHas('rkbus', function ($query) use ($tahunAnggaran) {
            $query->where('nama_tahun_anggaran', $tahunAnggaran) // Tambahkan filter tahun anggaran
                ->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                    $query->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d');
                });
        })
            ->whereHas('rkbus.user.ksp', function ($query) use ($id_ksp) {
                $query->where('id_ksp', $id_ksp);
            })
            ->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                $query->where('nama_tahun_anggaran', $tahunAnggaran);
            })
            ->get();

        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait

        // Ambil data rkbu berdasarkan filter sub_kategori_rkbu
        // Query dengan relasi dan filter yang dibutuhkan
        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');

        // dd($query);

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->has('id_status_validasi_rka') && $request->id_status_validasi_rka != '') {
            $query->where('id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        // Dapatkan data rkbu
        $rkbus_total = $query->select('rkbus.*')->get();

        // Ambil total anggaran berdasarkan id_user dari session
        $total_anggaran = $rkbus_total->sum('total_anggaran');

        // Dapatkan data rkbu
        $rkbus = $query->with([
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program'
        ])->get();

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


        $categories = JenisBelanja::where('id_jenis_belanja', '9cdfcfed-6061-485b-8e20-662a776d0d06') // Filter berdasarkan id_jenis_belanja
            ->whereHas('jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus.users', function ($query) use ($id_ksp, $tahunAnggaran) {
                // Kondisi untuk id_jenis_kategori_rkbu yang spesifik dan tahun_anggaran
                $query->where('users.id_ksp', $id_ksp)
                    ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
                    ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                    ->where('nama_tahun_anggaran', $tahunAnggaran);
            })
            ->with([
                'jenis_kategori_rkbus' => function ($query) use ($id_ksp, $tahunAnggaran) {
                    $query->whereHas('obyek_belanjas.kategori_rkbus.sub_kategori_rkbus.rkbus.users', function ($query) use ($id_ksp, $tahunAnggaran) {
                        // Kondisi untuk id_jenis_kategori_rkbu dan tahun_anggaran
                        $query->where('users.id_ksp', $id_ksp) // id_ksp berasal dari tabel users melalui relasi user
                            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
                            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas' => function ($query) use ($id_ksp, $tahunAnggaran) {
                    $query->whereHas('kategori_rkbus.sub_kategori_rkbus.rkbus.users', function ($query) use ($id_ksp, $tahunAnggaran) {
                        // Tampilkan hanya data dengan id_user yang sesuai
                        $query->where('users.id_ksp', $id_ksp) // id_ksp berasal dari tabel users melalui relasi user
                            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus' => function ($query) use ($id_ksp, $tahunAnggaran) {
                    $query->whereHas('sub_kategori_rkbus.rkbus.users', function ($query) use ($id_ksp, $tahunAnggaran) {
                        // Tampilkan hanya data dengan id_user yang sesuai
                        $query->where('users.id_ksp', $id_ksp) // id_ksp berasal dari tabel users melalui relasi user
                            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                },
                'jenis_kategori_rkbus.obyek_belanjas.kategori_rkbus.sub_kategori_rkbus' => function ($query) use ($id_ksp, $tahunAnggaran) {
                    $query->whereHas('rkbus.users', function ($query) use ($id_ksp, $tahunAnggaran) {
                        // Tampilkan hanya data dengan id_user dan tahun anggaran yang sesuai
                        $query->where('users.id_ksp', $id_ksp) // id_ksp berasal dari tabel users melalui relasi user
                            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                            ->where('nama_tahun_anggaran', $tahunAnggaran);
                    });
                }
            ])->get();

        // Inisialisasi TCPDF
        $pdf = new MYPDF();

        // Set informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nama Penulis');
        $pdf->SetTitle('Laporan RKBU');
        $pdf->SetSubject('Laporan RKBU');
        $pdf->SetKeywords('TCPDF, PDF, RKBU, example');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetFont('helvetica', '', 7); // Set font utama

        // Set resolusi halaman
        // $resolusi = array(240, 355);
        // $pdf->SetPageFormat($resolusi, 'P');

        // Mengatur margin halaman dan auto page break
        $pdf->SetMargins(15, 10, 15);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25); // Auto page break dengan margin bawah
        $pdf->setCellPaddings(1, 1, 1, 1); // Padding cell

        // Cek data judul headers dari database
        $judulHeaders = JudulHeader::first();

        // Cek dan atur gambar, gunakan gambar default jika tidak tersedia
        $gambar1 = $judulHeaders->gambar1 ? asset('storage/uploads/' . basename($judulHeaders->gambar1)) : asset('storage/uploads/foto.png');
        $gambar2 = $judulHeaders->gambar2 ? asset('storage/uploads/' . basename($judulHeaders->gambar2)) : asset('storage/uploads/foto.png');

        // Tambahkan halaman pertama
        $pdf->AddPage('L');

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
        $htmlBody = view('frontend.prints.print_rkbu_pegawai_pns', compact('rkbus', 'pdf', 'sub_kategori_rkbus', 'status_validasi_rka', 'total_anggaran', 'categories', 'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan'))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

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
