<?php

namespace App\Http\Controllers\MasterSpj;

use TCPDF;
use App\Pdf\MYPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingBelanja\Spj;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingBelanja\MasterSpj;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;
use App\Models\MasterBackend\SettingUser\AdminPendukung;

class PdfControllerSpjSuratPesanan extends Controller
{

    public function downloadPDF($id = null)
    {
        if (!$id) {
            return 'hello';
        }

        $tahunAnggaran = Session::get('tahun_anggaran');
        $master_spj = MasterSpj::findOrFail($id);
        $judul_headers = JudulHeader::first();
        $pendukung_ppk = AdminPendukung::all();
        $perusahaan = Perusahaan::all();
        $invoice = Spj::where('id_spj', $master_spj->id_spj)->first();

        $first_direktur = Pejabat::join('jabatans', 'jabatans.id_jabatan', '=', 'pejabats.id_jabatan')
            ->where('jabatans.nama_jabatan', 'Direktur')
            ->select('jabatans.*', 'pejabats.nama_pejabat', 'pejabats.nip_pejabat') // Pilih kolom yang ingin diambil
            ->first();


        $nama_direktur      = '...............';
        $nip_direktur       = '...............';

        // dd($first_pejabat->nip_pejabat);


        if ($first_direktur) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_direktur      = $first_direktur->nama_pejabat ?? '...............';
            $nip_direktur       = $first_direktur->nip_pejabat ?? '...............';
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
            ->where('master_spjs.id_master_spj', $id) // 🔥 filter sesuai ID master_spj
            ->select('master_spjs.*', 'perusahaans.*', 'admin_pendukungs.id_admin_pendukung_ppk', 'kategori_rkbus.*', 'sub_kategori_rkbus.*', 'rekening_belanjas.*', 'pejabat_pengadaans.*')
            ->distinct()
            ->first();

        $nama_hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $nama_tahun = [
            'Dua Ribu',
            'Dua Ribu Satu',
            'Dua Ribu Dua',
            'Dua Ribu Tiga',
            'Dua Ribu Empat',
            'Dua Ribu Lima',
            'Dua Ribu Enam',
            'Dua Ribu Tujuh',
            'Dua Ribu Delapan',
            'Dua Ribu Sembilan'
        ];
        $angka = [
            1 => 'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            11 => 'Sebelas',
            12 => 'Dua Belas',
            13 => 'Tiga Belas',
            14 => 'Empat Belas',
            15 => 'Lima Belas',
            16 => 'Enam Belas',
            17 => 'Tujuh Belas',
            18 => 'Delapan Belas',
            19 => 'Sembilan Belas',
            20 => 'Dua Puluh',
            21 => 'Dua Puluh Satu',
            22 => 'Dua Puluh Dua',
            23 => 'Dua Puluh Tiga',
            24 => 'Dua Puluh Empat',
            25 => 'Dua Puluh Lima',
            26 => 'Dua Puluh Enam',
            27 => 'Dua Puluh Tujuh',
            28 => 'Dua Puluh Delapan',
            29 => 'Dua Puluh Sembilan',
            30 => 'Tiga Puluh',
            31 => 'Tiga Puluh Satu'
        ];

        $tanggal = (int)($komponen_tanggal[2] ?? 0); // Mendapatkan tanggal (pastikan nilai valid)
        $bulan = (int)($komponen_tanggal[1] ?? 0);  // Mendapatkan bulan
        $tahun = (int)($komponen_tanggal[0] ?? 0);  // Mendapatkan tahun

        // Validasi tanggal ke angka
        $tanggal_teks = $angka[$tanggal] ?? 'Tanggal Tidak Valid';

        // Mengonversi bulan dan tahun
        $bulan_nama = $nama_bulan[$bulan] ?? 'Bulan Tidak Valid';
        $tahun_nama = $nama_tahun[$tahun - 2000] ?? 'Tahun Tidak Valid';

        // dd($total_non_ppn);

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');


        if ($invoice) {
            $keranjang = SpjDetail::where('spj_details.id_spj', $invoice->id_spj)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'spj_details.id_rkbu') // Join ke tabel rkbus
                ->join('spjs', 'spjs.id_spj', '=', 'spj_details.id_spj') // Join ke tabel spjs
                ->join('master_spjs', 'master_spjs.id_spj', '=', 'spjs.id_spj') // Join ke tabel spjs
                ->join('usulan_barangs', 'spjs.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang') // Join ke tabel usulan_barangs
                ->Join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail') // Left join ke tabel usulan_barang_details
                ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
                ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->select(
                    'spj_details.*',
                    'rkbus.nama_barang',
                    'usulan_barang_details.*',
                    'usulan_barangs.no_usulan_barang',
                    'kategori_rkbus.kode_kategori_rkbu',
                    'kategori_rkbus.nama_kategori_rkbu',
                    'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                    'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                    'rekening_belanjas.kode_rekening_belanja',
                    'rekening_belanjas.nama_rekening_belanja',
                    'master_spjs.tgl_surat_pesanan',
                    'master_spjs.jangka_waktu_pekerjaan',
                ) // Select kolom yang dibutuhkan
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }

        // dd($keranjang);

        // Format tanggal surat pesanan
        $tanggal_format = Carbon::parse($detail_master_spj->tgl_surat_pesanan)->format('d') . ' ' .
            ($nama_bulan[(int)Carbon::parse($detail_master_spj->tgl_surat_pesanan)->format('m')] ?? 'Bulan Tidak Valid') . ' ' .
            Carbon::parse($detail_master_spj->tgl_surat_pesanan)->format('Y');

        // Format tanggal jangka waktu pekerjaan
        $tanggal_format2 = Carbon::parse($detail_master_spj->jangka_waktu_pekerjaan)->format('d') . ' ' .
            ($nama_bulan[(int)Carbon::parse($detail_master_spj->jangka_waktu_pekerjaan)->format('m')] ?? 'Bulan Tidak Valid') . ' ' .
            Carbon::parse($detail_master_spj->jangka_waktu_pekerjaan)->format('Y');

        // Menghitung selisih hari
        $tanggal1 = Carbon::parse($keranjang->isNotEmpty() ? ($keranjang->first()->tgl_surat_pesanan) : null);
        $tanggal2 = Carbon::parse($keranjang->isNotEmpty() ? ($keranjang->first()->jangka_waktu_pekerjaan) : null);
        $selisih_hari = $tanggal2->diffInDays($tanggal1);

        $jumlah_hari =  $selisih_hari;
        $jumlah_hari_terbilang = terbilang($jumlah_hari + 1);

        $tgl_surat_pesanan      = $keranjang->isNotEmpty() ? formatTanggal($keranjang->first()->tgl_surat_pesanan) : null;
        $tgl_waktu_pekerjaan    = $keranjang->isNotEmpty() ? formatTanggal($keranjang->first()->jangka_waktu_pekerjaan) : null;

        $tgl_pesanan            = $tgl_surat_pesanan ? $tgl_surat_pesanan : 'Tanggal tidak tersedia';
        $tgl_pekerjaan          = $tgl_waktu_pekerjaan ? $tgl_waktu_pekerjaan : 'Tanggal tidak tersedia';
        $terbilang_total        = terbilangUang($sum_total_anggaran_usulan_barang);

        $data = [
            'invo'                            => $invo,
            'master_spj'                      => $master_spj,
            'detail_master_spj'               => $detail_master_spj,
            'selisih_hari'                    => $selisih_hari,
            'jumlah_hari_terbilang'           => $jumlah_hari_terbilang,
            'pendukung'                       => $pendukung_ppk,
            'tgl_pesanan'                     => $tgl_pesanan,
            'tgl_pekerjaan'                   => $tgl_pekerjaan,
            'terbilang_total'                 => $terbilang_total,
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
        ];


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

        $pdf->SetCompression(true);
        // Mengatur margin halaman dan auto page break
        $pdf->SetMargins(15, 15, 15);
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
        $html = '<table lines="none" bgcolor="white">
                <thead>
               <tr bgcolor="#ffffff">
               <th  width="20%" align="left"><h4></h4>
                    <h6 align="top" style="line-height:11;"><img src="assets/foto_rs/jakarta.png" width="65" height="65"  ></h6>
               </th> 
               <th  width="70%" align="center">
              <h4 style="font-weight: normal; line-height:0; font-size:11px;">' . $judulHeaders->header1 . '</h4>
               <h5 style="font-weight: bold; font-size:15px; line-height:1;">' . $judulHeaders->header4 . '</h5>
               <h5 style="font-weight: bold; font-size:12px; line-height:0;">' . $judulHeaders->nama_rs . '</h5>
                    <h3 style="font-weight: normal; font-size:11px; line-height:1.3;" align="center"; text="wrap">' . $judulHeaders->alamat_rs . ' </h3>
                    <h3 style="font-weight: normal; font-size:11px; line-height:0;" align="center">Telp. ' . $judulHeaders->tlp_rs . ' Faksimile. ' . $judulHeaders->tlp_rs . '  </h3>
                    <h3 style="font-weight: normal; font-size:11px; line-height:1;" align="center">' . $judulHeaders->header3 . '</h3>
                    <h3 style="font-weight: normal; font-size:11px; line-height:0;" align="center">' . $judulHeaders->wilayah . '</h3>
               </th>
               <hr>
               </tr>
               <tr bgcolor="#ffffff">
               <th  width="90%" align="right">
               <h5 style="font-weight: normal; font-size:11px; line-height:1; align:right;">Kode Pos 14130</h5>
               </th>
               </tr>
               </thead>
        </table>';


        // Tambahkan header ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Cek dan tambahkan halaman baru jika diperlukan sebelum menulis konten tabel
        $requiredSpace = 50; // Tentukan tinggi yang diperlukan untuk elemen (misalnya tabel)
        $this->checkPageBreak($pdf, $requiredSpace);

        // Tambahkan konten body dari view 'print_rkbu_modal_kantor'
        $htmlBody = view('master_spj.print.print_surat_pesanan', $data, compact('nama_direktur', 'judulHeaders', 'nip_direktur', 'nama', 'alamat', 'email', 'tlp'));

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        checkPageBreak($pdf, 70); // Memastikan ruang yang cukup untuk tanda tangan
        $htmlSignatureTable = '
             <table>
                <tr >
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="40%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%">Jakarta, ' . $tgl_pesanan . ' </td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">Pejabat Pembuat Komitmen</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="40%">Pejabat Pengadaan Barang/Jasa</td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%">' . ucwords(strtolower($judulHeaders->nama_rs)) . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="40%">' . ucwords(strtolower($judulHeaders->nama_rs)) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: center;" class="no-border" width="40%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: center; text:wrap" class="no-border" width="40%"></td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%">' . $detail_master_spj->nama_ppk . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="40%">' . $detail_master_spj->nama_ppbj . '</td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">NIP ' . $detail_master_spj->nip_ppk . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="40%">NIP ' . $detail_master_spj->nip_ppbj . '</td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: left;" class="no-border" width="20%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="40%"></td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="100%">Untuk dan atas nama</td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="30%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%">' . $detail_master_spj->nama_perusahaan . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="30%"></td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: center;" class="no-border" width="30%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: center;" class="no-border" width="40%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 5; text-align: center; text:wrap" class="no-border" width="30%"></td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="30%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%">' . $detail_master_spj->nama_direktur_perusahaan . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="30%"></td>
                </tr>
                <tr>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="30%"></td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">' . $detail_master_spj->jabatan_perusahaan . '</td>
                    <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="30%"></td>
                </tr>
            </table>';

        $pdf->writeHTML($htmlSignatureTable, true, false, true, false, '');

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
