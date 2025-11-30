<?php

namespace App\Http\Controllers\Rkbu\Administrator;

use App\Pdf\MYPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;

class PdfControllerPrintByFilterPegawai extends Controller
{
    public function print(Request $request)
    {
        $id_pejabat             = Session::get('id_pejabat');
        $id_ksp                 = session('id_ksp');
        $tahunAnggaran          = Session::get('tahun_anggaran');

        $id_tahun_anggaran   = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('id');
        $id_fase = session('id_fase');

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

        // $currentJenisKategori = $request->input(
        //     'jenis_kategori_rkbu',
        //     '9cdfd354-e43a-4461-9747-e15fb74038ac'
        // );

        // QUERY SAMA PERSIS DENGAN INDEX
        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->join('jabatans', 'pejabats.id_jabatan', '=', 'jabatans.id_jabatan')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran);
        // ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932');
        // ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '!=', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
        // ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', $currentJenisKategori);

        // FILTER SUB KATEGORI
        if ($request->filled('sub_kategori_rkbu')) {
            $query->where('rkbus.id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // FILTER STATUS VALIDASI RKA
        if ($request->filled('id_status_validasi_rka')) {
            $query->where('rkbus.id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        // AMBIL DATA
        $rkbus = $query->select('rkbus.*')->get();

        // Total anggaran
        $total_anggaran = $rkbus->sum('total_anggaran');

        $rkbus = $query->with([
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program'
        ])->get();

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

        if ($first_direktur) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_direktur      = $first_direktur->nama_pejabat ?? '...............';
            $nip_direktur       = $first_direktur->nip_pejabat ?? '...............';
        }

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


        $categories = DB::table('jenis_belanjas')
            ->join('jenis_kategori_rkbus', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
            ->join('obyek_belanjas', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu', '=', 'obyek_belanjas.id_jenis_kategori_rkbu')
            ->join('kategori_rkbus', 'obyek_belanjas.id_obyek_belanja', '=', 'kategori_rkbus.id_obyek_belanja')
            ->join('sub_kategori_rkbus', 'kategori_rkbus.id_kategori_rkbu', '=', 'sub_kategori_rkbus.id_kategori_rkbu')
            ->join('rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')

            // FILTER UTAMA
            // ->where('sumber_danas.id_sumber_dana', '=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
            // ->where('jenis_belanjas.id_jenis_belanja', '9cdfd042-e7cc-4008-ad0e-96d0a5452721')
            // ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', $currentJenisKategori)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            // ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            // ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '!=', '9d03d062-b7d6-490e-ad71-10d92c5628cb')

            // FILTER TAMBAHAN DARI FORM
            ->when($request->filled('sub_kategori_rkbu'), function ($q) use ($request) {
                $q->where('rkbus.id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
            })
            ->when($request->filled('id_status_validasi_rka'), function ($q) use ($request) {
                $q->where('rkbus.id_status_validasi_rka', $request->id_status_validasi_rka);
            })

            ->select(
                'jenis_belanjas.kode_jenis_belanja',
                'jenis_belanjas.nama_jenis_belanja',
                'jenis_kategori_rkbus.kode_jenis_kategori_rkbu',
                'jenis_kategori_rkbus.nama_jenis_kategori_rkbu',
                'obyek_belanjas.kode_obyek_belanja',
                'obyek_belanjas.nama_obyek_belanja',
                'kategori_rkbus.kode_kategori_rkbu',
                'kategori_rkbus.nama_kategori_rkbu',
                'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'rkbus.*', // ambil semua field barang
                'users.nama_lengkap',
                'units.nama_unit'
            )
            ->orderBy('kode_jenis_belanja')
            ->orderBy('kode_jenis_kategori_rkbu')
            ->orderBy('kode_obyek_belanja')
            ->orderBy('kode_kategori_rkbu')
            ->orderBy('kode_sub_kategori_rkbu')
            ->get();


        if (ob_get_length()) {
            ob_end_clean();
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
        $pdf->SetFont('helvetica', '', 7); // Set font utama

        // Set resolusi halaman
        // $resolusi = array(240, 355);
        // $pdf->SetPageFormat($resolusi, 'P');

        $pdf->SetCompression(true);
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

        // HTML VIEW
        $htmlBody = view('rkbu.formatform_serverside.print_pdf_pegawai', compact(
            'rkbus',
            'tanggal_perencanaans',
            'nama_rumah_sakit',
            'nip_direktur',
            'nama_direktur',
            'total_anggaran',
            'categories',
            'namaProgram',
            'namaKegiatan',
            'namaSubKegiatan',
            'kodeProgram',
            'kodeKegiatan',
            'kodeSubKegiatan'
        ))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        checkPageBreak($pdf, 50); // 50 untuk memastikan ruang yang cukup untuk tanda tangan
        $htmlSignatureTable = '
            <table rules="none">
                <tr class="table-rows-he">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center"></th> 
                </tr>   
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">Jakarta, ' . implode(', ', $tanggal_perencanaans) . '</th>  
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">Direktur ' . ucwords(strtolower($nama_rumah_sakit)) . '</th> 
                </tr>
                <p></p>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nama_direktur . '</th> 
                </tr>
                <tr class="table-rows" bgcolor="#ffffff">
                    <th scope="row" width="20%" align="center"></th>
                    <th width="45%" align="center"></th>
                    <th width="30%" align="center">' . $nip_direktur . '</th> 
                </tr>
            </table>';

        $pdf->writeHTML($htmlSignatureTable, true, false, true, false, '');

        // Tampilkan PDF sebagai preview di browser
        return response($pdf->Output('rkbu_data_preview.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="rkbu_data_preview.pdf"');
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
