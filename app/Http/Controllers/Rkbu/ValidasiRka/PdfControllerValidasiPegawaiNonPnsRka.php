<?php

namespace App\Http\Controllers\Rkbu\ValidasiRka;

use App\Pdf\MYPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class PdfControllerValidasiPegawaiNonPnsRka extends Controller
{
    public function downloadPDF(Request $request)
    {
        // Ambil id_user dari session
        $id_pejabat             = session('id_pejabat');
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

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
            ->select('sub_kategori_rkbus.*') // Pilih data sub_kategori_rkbu
            ->distinct() // Hilangkan duplikasi
            ->get();

        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait


        // Ambil data rkbu berdasarkan filter sub_kategori_rkbu
        // Query dengan relasi dan filter yang dibutuhkan
        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->join('jabatans', 'pejabats.id_jabatan', '=', 'jabatans.id_jabatan')
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d');

        // dd($query);

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->has('id_status_validasi_rka') && $request->id_status_validasi_rka != '') {
            $query->where('id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        $first_pejabat  = $query->select('pejabats.*', 'jabatans.nama_jabatan')->first();

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


        // Dapatkan data rkbu
        $rkbus_total = $query->select('rkbus.*')->get();

        // Ambil total anggaran berdasarkan id_user dari session
        $total_anggaran = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '=', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
            ->sum('total_anggaran');

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

        $categories = DB::table('jenis_belanjas')
            ->join('jenis_kategori_rkbus', 'jenis_belanjas.id_jenis_belanja', '=', 'jenis_kategori_rkbus.id_jenis_belanja')
            ->join('obyek_belanjas', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu', '=', 'obyek_belanjas.id_jenis_kategori_rkbu')
            ->join('kategori_rkbus', 'obyek_belanjas.id_obyek_belanja', '=', 'kategori_rkbus.id_obyek_belanja')
            ->join('sub_kategori_rkbus', 'kategori_rkbus.id_kategori_rkbu', '=', 'sub_kategori_rkbus.id_kategori_rkbu')
            ->join('rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('jenis_belanjas.id_jenis_belanja', '9cdfd042-e7cc-4008-ad0e-96d0a5452721')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->where('users.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '=', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
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
                'rkbus.nama_pegawai',
                'users.nama_lengkap',
                'units.nama_unit',
                'rkbus.tempat_lahir',
                'rkbus.tanggal_lahir',
                'rkbus.jabatan',
                'rkbus.pendidikan',
                'rkbus.nomor_kontrak',
                'rkbus.tmt_pegawai',
                'rkbus.tahun_tmt',
                'rkbus.bulan_tmt',
                'rkbus.total_gaji_pokok',
                'rkbus.remunerasi',
                'rkbus.koefisien_remunerasi',
                'rkbus.total_anggaran'
            )
            ->distinct() // Ini memastikan hasil tidak duplikat
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
        $htmlBody = view('rkbu.partials.print_rkbu_pegawai_admin', compact('rkbus', 'tanggal_perencanaans', 'jabatan_kabag', 'nama_rumah_sakit', 'nama_pejabat', 'nip_pejabat_kabag', 'nip_direktur', 'nama_direktur', 'pdf', 'sub_kategori_rkbus', 'status_validasi_rka', 'total_anggaran', 'categories', 'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan'))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        // Tampilkan PDF sebagai preview di browser
        return response($pdf->Output('rkbu_data_preview.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="rkbu_data_preview.pdf"');

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
