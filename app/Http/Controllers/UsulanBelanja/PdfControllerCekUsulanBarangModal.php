<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Pdf\MYPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingUser\LevelUser;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class PdfControllerCekUsulanBarangModal extends Controller
{
    public function downloadPDFCekBarjas(Request $request, $id_usulan_barang)
    {

        // Ambil id_ksp dari session
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait
        $inputIdUsulanBarang = request()->input('id_usulan_barang'); // Ambil input id_usulan_barang dari request

        $usulan_barangs = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->where('status_usulan_barang', 'Selesai')
            ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('usulan_barangs.id_user', $id_user)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->orderby('usulan_barangs.id_usulan_barang', 'DESC')
            ->select('usulan_barangs.*', 'rkbus.*', 'users.nama_lengkap as nama_perencana')
            ->distinct()
            ->get();

        $id_usulan = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->where('status_usulan_barang', 'Pending')
            ->where('id_user', $id_user)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderby('usulan_barangs.id_usulan_barang', 'DESC')
            ->select('usulan_barangs.*')
            ->distinct()
            ->get();

        //Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->whereIn('jenis_kategori_rkbus.id_jenis_kategori_rkbu', ['9cf70e1d-18e7-40fe-bdd3-b7dabf61877d', '9cf70e31-9b9e-4dea-8b39-5459f23f3f51']) // Kategori modal
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->distinct() // Menghapus duplikasi berdasarkan sub_kategori_rkbus yang sama
            ->get();

        // Ambil detail invoice berdasarkan id_usulan_barang
        $invoice = UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)->first();
        $judul_headers = JudulHeader::first();
        $qrcode = UsulanBarang::where('id_usulan_barang', $id_usulan_barang)->first();

        // Ambil data rkbu berdasarkan filter sub_kategori_rkbu
        // Query dengan relasi dan filter yang dibutuhkan
        $query = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu',
        ])
            ->where('id_user', session('id_user'))  // Filter berdasarkan id_user
            ->where('nama_tahun_anggaran', session('tahun_anggaran'))
            ->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                // Filter berdasarkan id_jenis_kategori_rkbu
                $query->whereIn('jenis_kategori_rkbus.id_jenis_kategori_rkbu', ['9cf70e1d-18e7-40fe-bdd3-b7dabf61877d', '9cf70e31-9b9e-4dea-8b39-5459f23f3f51']);
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

        // Pastikan variabel judul_headers ada
        $judul_header1          = $judul_headers->header1 ?? 'Judul Tidak Ada';
        $nama                   = $judul_headers->nama_rs ?? 'Nama Tidak Ada';
        $alamat                 = $judul_headers->alamat_rs ?? 'Alamat Tidak Ada';
        $tlp                    = $judul_headers->tlp_rs ?? 'Tlp Tidak Ada';
        $email                  = $judul_headers->email_rs ?? 'Email Tidak Ada';
        $website                = $judul_headers->header3 ?? 'Website Tidak Ada';
        $gambar1                = $judul_headers->gambar1 ?? 'Website Tidak Ada';
        $gambar2                = $judul_headers->gambar2 ?? 'Website Tidak Ada';

        // Ambil total anggaran usulan barang
        $sum_total_anggaran_usulan_barang = UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)
            ->sum('total_anggaran_usulan_barang');

        $sum_total_ppn = UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)
            ->sum('total_ppn');

        $sum_total_anggaran_usulan_barang_non_ppn =   $sum_total_anggaran_usulan_barang - $sum_total_ppn;

        // dd($sum_total_ppn);

        // Ambil data invoice cetak
        $invo = DB::table('usulan_barang_details')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'usulan_barang_details.id_usulan_barang')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'kategori_rkbus.id_kategori_rkbu', '=', 'sub_kategori_rkbus.id_kategori_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rekenings.id_sub_kategori_rekening', '=', 'sub_kategori_rkbus.id_sub_kategori_rekening')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans', 'kegiatans.id_kegiatan', '=', 'sub_kegiatans.id_kegiatan')
            ->join('programs', 'programs.id_program', '=', 'kegiatans.id_program')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('usulan_barang_details.id_usulan_barang', $id_usulan_barang) // Filter by id_usulan_barang
            ->select(
                'usulan_barang_details.*',
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
                'usulan_barangs.*'
            )
            ->first();

        $jumlahAnggaran = DB::table('rkbus')
            ->where('id_sub_kategori_rkbu', $invo->id_sub_kategori_rkbu)
            ->where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->selectRaw('SUM(total_anggaran) as total_anggaran, SUM(sisa_anggaran_rkbu) as sisa_anggaran')
            ->first();

        // dd($jumlahAnggaran, $invo->id_sub_kategori_rkbu);

        // Cek apakah invoice ditemukan
        if ($invoice) {
            $keranjang = UsulanBarangDetail::where('usulan_barang_details.id_usulan_barang', $invoice->id_usulan_barang)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
                ->select('usulan_barang_details.*', 'rkbus.nama_barang', 'rkbus.spek', 'usulan_barang_details.created_at')
                ->orderBy('usulan_barang_details.created_at', 'asc') // urutkan usulan berdasarkan waktu
                ->get();

            // Ambil semua data riwayat dari tabel usulan_barang_details
            $riwayatSemua = UsulanBarangDetail::select('id_rkbu', 'jumlah_usulan_barang', 'created_at')->get();
        } else {
            $keranjang = collect();
            $riwayatSemua = collect();
        }

        $riwayatData = UsulanBarangDetail::select('id_rkbu', 'jumlah_usulan_barang', 'created_at')->get();

        $nama_valid = UsulanBarang::join('users as perencana', 'usulan_barangs.nama_valid_perencana', '=', 'perencana.id_user')
            ->leftJoin('users as kabag', 'usulan_barangs.nama_valid_rka', '=', 'kabag.id_user')
            ->leftJoin('users as direktur', 'usulan_barangs.nama_valid_direktur', '=', 'direktur.id_user')
            ->Join('users as pptk', 'usulan_barangs.nama_valid_pptk', '=', 'pptk.id_user')
            ->where('usulan_barangs.id_usulan_barang', $id_usulan_barang)
            ->select(
                'perencana.nama_lengkap as nama_valid_perencana',
                'pptk.nama_lengkap as nama_valid_pptk',
                'kabag.nama_lengkap as nama_valid_rka',
                'direktur.nama_lengkap as nama_valid_direktur',
                'usulan_barangs.*'
            )
            ->first();


        $direktur = Pejabat::join('jabatans', 'jabatans.id_jabatan', '=', 'pejabats.id_jabatan')
            ->where('jabatans.nama_jabatan', 'Direktur')
            ->where('pejabats.status', 'aktif')
            ->select('pejabats.nama_pejabat', 'pejabats.nip_pejabat', 'jabatans.nama_jabatan')
            ->first();

        $jabatan_kabag2 = $qrcode?->validRka?->pejabat?->jabatan?->nama_jabatan ?? $qrcode?->user?->pejabat?->jabatan?->nama_jabatan;
        $nama_kabag2    = $qrcode?->validRka?->pejabat?->nama_pejabat ?? $qrcode?->user?->pejabat?->nama_pejabat;
        $nip_kabag2     = $qrcode?->validRka?->pejabat?->nip_pejabat ?? $qrcode?->user?->pejabat?->nip_pejabat;

        $jabatan_dir    = $direktur?->nama_jabatan ?? 'Data tidak tersedia';
        $nama_dir       = $direktur?->nama_pejabat ?? 'Data tidak tersedia';
        $nip_dir        = $direktur?->nip_pejabat ?? 'Data tidak tersedia';

        $usulan             = UsulanBarang::find($id_usulan_barang);
        $pptk               = User::find($usulan->nama_valid_pptk);
        $perencana          = User::find($usulan->nama_valid_perencana);
        $pengusul           = User::find($usulan->id_user);
        $jabatan_pptk       = $pptk && $pptk->id_level
            ? LevelUser::find($pptk->id_level)?->nama_level_user
            : '...............';
        $jabatan_perencana  = $perencana && $perencana->id_level
            ? LevelUser::find($perencana->id_level)?->nama_level_user
            : '...............';

        $nip_pengusul   = $pengusul->nip_user ?? '...............';
        // $nama_pptk1     = $pptk->nama_lengkap ?? '...............';
        // $nama_perencana = $perencana->nama_lengkap ?? '...............';
        // $nip_perencana  = $perencana->nip_user ?? '...............';
        // $nip_pptk1      = $pptk->nip_user ?? '...............';

        $nama_pptk1          = $pptk->nama_lengkap ?? null;
        $nama_perencana      = $perencana->nama_lengkap ?? null;
        $nip_pptk1           = $pptk->nip_user ?? null;
        $nip_perencana       = $perencana->nip_user ?? null;
        $jabatan_pptk        = $pptk->levelUser->nama_level_user ?? null;
        $jabatan_perencana   = $perencana->levelUser->nama_level_user ?? null;


        $kolom_nama_kiri = ($nama_perencana && $nama_pptk1)
            ? "$nama_perencana || $nama_pptk1"
            : ($nama_perencana ?? $nama_pptk1 ?? '...............');

        $kolom_jabatan_kiri = ($jabatan_perencana && $jabatan_pptk)
            ? "$jabatan_perencana || $jabatan_pptk"
            : ($jabatan_perencana ?? $jabatan_pptk ?? '...............');

        $kolom_nip_kiri = ($nip_perencana && $nip_pptk1)
            ? "NIP $nip_perencana || $nip_pptk1"
            : (
                $nip_perencana ? "NIP $nip_perencana" : ($nip_pptk1 ? "NIP $nip_pptk1" : "NIP ...............")
            );


        if ($nama_valid) {
            // Ambil nama valid perencana, kabag, dan direktur
            $nama_perencana     = $nama_valid->ValidPerencana->nama_lengkap ?? '...............';
            $nama_pptk          = $nama_valid->ValidPptk->nama_lengkap ?? '...............';
            $nama_kabag         = $nama_valid->ValidRka->nama_lengkap ?? '...............';
            $nama_direktur      = $nama_valid->ValidDirektur->nama_lengkap ?? '...............';
        } else {
            // Jika tidak ditemukan, berikan nilai default
            // $nama_perencana     = '...............';
            $nama_pptk          = '...............';
            $nama_kabag         = '...............';
            $nama_direktur      = '...............';
        }


        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');
        $qrcodepptk = $qrcode->qrcode_pengusul ? asset('storage/qrcodes/' . basename($qrcode->qrcode_pptk)) : asset('storage/uploads/foto.png');
        $qrcodeksp = $qrcode->qrcode_pengusul ? asset('storage/qrcodes/' . basename($qrcode->qrcode_pengusul)) : asset('storage/uploads/foto.png');
        $qrcodeperencana = $qrcode->qrcode_perencana ? asset('storage/qrcodes/' . basename($qrcode->qrcode_perencana)) : asset('storage/uploads/foto.png');
        $qrcodekabag = $qrcode->qrcode_kabag ? asset('storage/qrcodes/' . basename($qrcode->qrcode_kabag)) : asset('storage/uploads/foto.png');
        $qrcodedirektur = $qrcode->qrcode_direktur ? asset('storage/qrcodes/' . basename($qrcode->qrcode_direktur)) : asset('storage/uploads/foto.png');
        $nama_pengusul = $qrcode->nama_pengusul_barang ?? '...............';

        // Siapkan data untuk dikirim ke view
        $data = [
            'no_inv'            => $invo,
            'invoice'           => $invoice,
            'jumlahAnggaran'    => $jumlahAnggaran,
            'keranjang'         => $keranjang,
            'riwayatData'       => $riwayatData,
            'get_total'         => $sum_total_anggaran_usulan_barang,
            'sum_ppn'           => $sum_total_ppn,
            'judul_header1'     => $judul_header1,
            'nama'              => $nama,
            'alamat'            => $alamat,
            'tlp'               => $tlp,
            'email'             => $email,
            'website'           => $website,
            'gambar1'           => $gambar1,
            'gambar2'           => $gambar2,
            'rkbus'             => $rkbus,
            'qrcodeksp'         => $qrcodeksp,
            'qrcodepptk'         => $qrcodepptk,
            'qrcodekabag'       => $qrcodekabag,
            'qrcodeperencana'   => $qrcodeperencana,
            'qrcodedirektur'    => $qrcodedirektur,
            'nama_pptk'         => $nama_pptk1,
            'nama_perencana'    => $nama_perencana,
            'nama_kabag'        => $nama_kabag,
            'nama_direktur'    => $nama_direktur,
            'nama_pengusul'    => $nama_pengusul,
            'nip_pengusul'    => $nip_pengusul,
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
        $pdf->SetMargins(15, 10, 15);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25); // Auto page break dengan margin bawah
        $pdf->setCellPaddings(1, 1, 1, 1); // Padding cell

        $judulHeaders = JudulHeader::first();

        // Cek apakah gambar1 ada, jika tidak gunakan gambar alias
        $gambar1 = $judulHeaders->gambar1 ? asset('storage/judul_header/' . basename($judulHeaders->gambar1)) : asset('storage/judul_header/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judulHeaders->gambar2 ? asset('storage/judul_header/' . basename($judulHeaders->gambar2)) : asset('storage/judul_header/foto.png');
        // Buat konten HTML untuk PDF (border tabel diatur ke 0.5)
        $pdf->AddPage('P');
        $html = '<table lines="none" bgcolor="white">
               <tr bgcolor="#ffffff">
               <th  width="10%" align="left"><h4></h4>
                    <h6 align="center"><img src="' . $gambar2 . '" width="65" height="65"  ></h6>
               </th> 
               <th  width="80%" align="center">
                    <h3 align="center" style="font-size:9px; font-weight: normal;">' . $judulHeaders->header1 . '</h3>
                    <h3 style="font-weight: normal; font-size:11px; line-height:0;" align="center">' . $judulHeaders->header4 . '</h3>
                    <h3 style="font-weight: normal; font-size:9px; " align="center">' . $judulHeaders->nama_rs . '</h3>
                    <h3 align="center" style="font-size:8px; font-weight: normal; line-height:0;">' . $judulHeaders->alamat_rs . '</h3>
                    <h3 align="center" style="font-size:8px; font-weight: normal; ">Telp. ' . $judulHeaders->tlp_rs . ' Faksimile. ' . $judulHeaders->tlp_rs . '</h3>
                    <h3 align="center" style="font-size:8px; font-weight: normal; line-height:0;">' . $judulHeaders->header3 . '</h3>
               </th>
               <th  width="10%" align="right"><h4></h4>
                    <h6 align="center"><img src="" width="75" height="45"  ></h6>
               </th> 
               </tr>
               <hr>
        </table>';

        // Tambahkan header ke PDF
        $pdf->writeHTML($html, false, false, true, false, '');

        // Cek dan tambahkan halaman baru jika diperlukan sebelum menulis konten tabel
        $requiredSpace = 50; // Tentukan tinggi yang diperlukan untuk elemen (misalnya tabel)
        $this->checkPageBreak($pdf, $requiredSpace);

        $htmlBody = view('usulan_belanja.partials.print_cek_usulan_belanja_barjas', $invoice, $data, compact('rkbus', 'sub_kategori_rkbus', 'status_validasi_rka',  'namaProgram', 'namaKegiatan', 'namaSubKegiatan', 'kodeProgram', 'kodeKegiatan', 'kodeSubKegiatan'))->render();

        // Tuliskan konten body ke PDF
        $pdf->writeHTML($htmlBody, true, false, true, false, '');

        checkPageBreak($pdf, 70); // Memastikan ruang yang cukup untuk tanda tangan
        $htmlSignatureTable = '
            <table rules="none">
                <tr bgcolor="#ffffff">
                    <th colspan="8" width="87.1%" align="right"><b>Sub Total</b></th>
                    <th width="13%" align="center"><b>' . number_format($sum_total_anggaran_usulan_barang_non_ppn, 0, ',', ',') . '</b></th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th colspan="8" width="87.1%" align="right"><b>PPN</b></th>
                    <th width="13%" align="center"><b>' . number_format($sum_total_ppn, 0, ',', '.') . '</b></th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th colspan="8" width="87.1%" align="right"><b>Total Anggaran</b></th>
                    <th width="13%" align="center"><b>' . number_format($sum_total_anggaran_usulan_barang, 0, ',', ',') . '</b></th>
                </tr>
            </table>
            <p>
    
        <!-- Footer Section -->
        <footer>
            <table rules="none">
                <tr bgcolor="#ffffff">
                    <th width="20%" align="center"></th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center">Jakarta, ' . \Carbon\Carbon::parse($invo->created_at)->translatedFormat('d F Y') . '</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="20%" align="center">' . $kolom_jabatan_kiri . '</th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center">KSP/Ka. Instalasi</th>
                </tr>
                <tr>
                    <th width="20%" align="center" style="height: 40px;"><img src="' . $qrcodepptk . '" alt="QR Code Pengusul" width="40" height="40"></th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center" style="height: 40px;"><img src="' . $qrcodeksp . '" alt="QR Code Pengusul" width="40" height="40"></th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="20%" align="center">' . $kolom_nama_kiri . '</th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center">' . $nama_pengusul . '</th>
                </tr>
                 <tr bgcolor="#ffffff">
                    <th width="20%" align="center">' . $kolom_nip_kiri  . '</th>
                    <th width="55%" align="center"></th>
                    <th width="20%" align="center">NIP ' . $nip_pengusul . '</th>
                </tr>
            </table><p>
            
            <table rules="none">
                <tr bgcolor="#ffffff">
                    <th width="50%" align="center">' . $jabatan_kabag2 . '</th>
                    <th width="50%" align="center">' . $jabatan_dir . '</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="50%" align="center" style="height: 40px;"><img src="' . $qrcodekabag . '" alt="QR Code Pengusul" width="40" height="40"></th>
                    <th width="50%" align="center" style="height: 40px;"><img src="' . $qrcodedirektur . '" alt="QR Code Pengusul" width="40" height="40"></th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="50%" align="center">' . $nama_kabag2 . '</th>
                    <th width="50%" align="center">' . $nama_dir . '</th>
                </tr>
                 <tr bgcolor="#ffffff">
                    <th width="50%" align="center">NIP ' . $nip_kabag2 . '</th>
                    <th width="50%" align="center">NIP ' . $nip_dir . '</th>
                </tr>
            </table>


            <p></p>
            <table rules="none">
                <tr bgcolor="#ffffff">
                    <th width="50%" align="Left">Catatan :</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="50%" align="Left" style="font-weight: normal; font-size:6px;">' .  $invo->keterangan_pptk . '</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="50%" align="Left" style="font-weight: normal; font-size:6px;">' . $invo->keterangan_kabag . '</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="50%" align="Left" style="font-weight: normal; font-size:6px;">' . $invo->keterangan_direktur  . '</th>
                </tr>
            </table></footer>';

        $pdf->writeHTML($htmlSignatureTable, true, false, true, false, '');


        // $filePath = storage_path('app/public/rkbu_data_preview.pdf');
        // $pdf->Output($filePath, 'F'); // Simpan file ke lokasi yang ditentukan

        // // Berikan respons download
        // return response()->download($filePath)->deleteFileAfterSend(true); // Hapus setelah diunduh

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
