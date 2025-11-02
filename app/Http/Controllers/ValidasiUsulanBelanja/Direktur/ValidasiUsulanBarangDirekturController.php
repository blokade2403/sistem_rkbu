<?php

namespace App\Http\Controllers\ValidasiUsulanBelanja\Direktur;

use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class ValidasiUsulanBarangDirekturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id_sub_kategori_rkbu = null)
    {
        // dd(session()->all());

        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $id_pejabat          = session('id_pejabat');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $inputIdUsulanBarang = request()->input('id_usulan_barang'); // Ambil input id_usulan_barang dari request
        $id_sub_kategori_rkbu = request()->input('id_sub_kategori_rkbu');

        // Ambil usulan barang yang pending
        $usulan_barangs = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            //->where('usulan_barangs.id_user', $id_user)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('status_usulan_barang', 'Selesai')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('usulan_barangs.*')
            ->get();

        $id_usulan = UsulanBarang::join('users', 'usulan_barangs.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->where('usulan_barangs.tahun_anggaran', $tahunAnggaran)
            ->where('usulan_barangs.status_usulan_barang', 'Selesai')
            ->where('usulan_barangs.status_validasi_kabag', 'Disetujui Kabag')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->leftJoin('usulan_barang_details', 'usulan_barang_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->select('usulan_barangs.*', 'usulan_barangs.id_usulan_barang') // Ambil detail barang juga jika diperlukan
            ->distinct() // Menghapus duplikasi jika ada
            ->get();

        $sub_kategori_rkbus = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')

            //->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->distinct() // Menghapus duplikasi berdasarkan sub_kategori_rkbus yang sama
            ->get();

        // Ambil semua barang sesuai dengan sub_kategori_rkbu dan usulan yang diinputkan
        $get_barangs = [];

        foreach ($usulan_barangs as $yek) {
            $barang = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->join('pejabats', 'ksps.id_pejabat', '=', 'pejabats.id_pejabat')
                ->where('rkbus.id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_sub_kategori_rkbu', $yek->id_sub_kategori_rkbu) // Filter by sub_kategori_rkbu for this usulan
                ->select('rkbus.*')
                ->get();

            // Simpan data barang per usulan
            $get_barangs[$yek->id_usulan_barang] = $barang;
        }

        return view('validasi_usulan_belanja.cek_usulan_belanja', compact('usulan_barangs', 'get_barangs', 'id_usulan', 'sub_kategori_rkbus'), ['routePrefix' => 'validasi_usulan_barang_direkturs']);
    }

    public function updateValidasi(Request $request, $no_usulan_barang)
    {
        // Validasi input dari form
        $request->validate([
            'status_validasi_direktur' => 'required',
            'keterangan_direktur'      => 'nullable|string',
        ]);

        // Cari data berdasarkan no_usulan_barang
        $usulanBarang = UsulanBarang::where('no_usulan_barang', $no_usulan_barang)->firstOrFail();

        // Jika status revisi
        if ($request->input('status_validasi_direktur') === 'Revisi') {
            // Reset status sesuai requirement
            $usulanBarang->status_permintaan_barang = 'Proses Usulan Barang';
            $usulanBarang->status_usulan_barang     = 'Pending';
            $usulanBarang->tgl_validasi_pptk        = null;
            $usulanBarang->tgl_validasi_kabag       = null;
            $usulanBarang->tgl_validasi_direktur    = null;
            $usulanBarang->status_validasi_pptk     = null;
            $usulanBarang->status_validasi_kabag    = null;

            // Hapus QR Code lama kalau ada
            if ($usulanBarang->qrcode_pengusul && Storage::disk('public')->exists($usulanBarang->qrcode_pengusul)) {
                Storage::disk('public')->delete($usulanBarang->qrcode_pengusul);
            }
            if ($usulanBarang->qrcode_pptk && Storage::disk('public')->exists($usulanBarang->qrcode_pptk)) {
                Storage::disk('public')->delete($usulanBarang->qrcode_pptk);
            }
            if ($usulanBarang->qrcode_kabag && Storage::disk('public')->exists($usulanBarang->qrcode_kabag)) {
                Storage::disk('public')->delete($usulanBarang->qrcode_kabag);
            }
            $usulanBarang->qrcode_pengusul  = null;
            $usulanBarang->qrcode_pptk      = null;
            $usulanBarang->qrcode_kabag     = null;

            // Update validasi direktur
            $usulanBarang->status_validasi_direktur = 'Revisi';
            $usulanBarang->tgl_validasi_direktur    = now();
            $usulanBarang->keterangan_direktur      = $request->input('keterangan_direktur');
            $usulanBarang->nama_valid_direktur      = auth()->user()->id_user;

            $usulanBarang->save();

            return redirect()->route('validasi_usulan_barang_direkturs.index')
                ->with('success', 'Data usulan barang berhasil diubah ke status Revisi.');
        }

        // Kalau bukan Revisi → lanjut proses validasi normal
        $usulanBarang->status_permintaan_barang = $request->input('status_validasi_direktur');
        $usulanBarang->tgl_validasi_direktur    = now();
        $usulanBarang->keterangan_direktur      = $request->input('keterangan_direktur');
        $usulanBarang->status_validasi_direktur = $request->input('status_validasi_direktur');
        $usulanBarang->nama_valid_direktur      = auth()->user()->id_user;

        // Pastikan data relasi ksp tidak null
        $nipKsp      = $usulanBarang->validRka ? $usulanBarang->validRka->nip_user : 'NIP Tidak Ditemukan';
        $namaPengusul = $usulanBarang->validRka ? $usulanBarang->validRka->nama_lengkap : 'Pengusul Tidak Ditemukan';

        // Data untuk QR code
        $dataQR = "Telah divalidasi oleh: " . $namaPengusul .
            "\nNIP: " . $nipKsp .
            "\nNo Usulan: " . $usulanBarang->no_usulan_barang .
            "\nTanggal: " . $usulanBarang->tgl_validasi_direktur->format('d-m-Y');

        // Generate QR code
        $qrcode = new QrCode($dataQR);
        $qrcode->setSize(300);

        $writer = new PngWriter();
        $result = $writer->write($qrcode);

        // Simpan QR Code ke folder public/qrcodes
        $qrcodePath = 'qrcodes/qrcode_direktur' . $usulanBarang->no_usulan_barang . '.png';
        Storage::disk('public')->put($qrcodePath, $result->getString());

        // Simpan path QR code
        $usulanBarang->qrcode_direktur = $qrcodePath;

        $usulanBarang->save();

        return redirect()->route('validasi_usulan_barang_direkturs.index')
            ->with('success', 'Data usulan barang berhasil divalidasi dan QR code berhasil dihasilkan.');
    }


    public function keranjang($id_usulan_barang)
    {
        // Ambil detail invoice berdasarkan id_usulan_barang
        $invoice = UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)->first();
        $judul_headers = JudulHeader::first();

        // Cek apakah invoice ditemukan (null atau tidak)
        if (is_null($invoice)) {
            // Jika null, kembalikan dengan pesan error atau lakukan tindakan lain
            return redirect()->back()->with('error', 'Data belum dipilih atau tidak ditemukan. Klik tombol +Barang Terlebih Dahulu');
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

        // Cek apakah invoice ditemukan
        if ($invoice) {
            $keranjang = UsulanBarangDetail::where('usulan_barang_details.id_usulan_barang', $invoice->id_usulan_barang)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu') // Join ke tabel rkbus
                ->select('usulan_barang_details.*', 'rkbus.nama_barang', 'rkbus.spek') // Select kolom yang diinginkan dari rkbus
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }

        // dd($keranjang);

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');

        // Siapkan data untuk dikirim ke view
        $data = [
            'no_inv'            => $invo,
            'invoice'           => $invoice,
            'keranjang'         => $keranjang,
            'get_total'         => $sum_total_anggaran_usulan_barang,
            'ppn'               => $sum_total_ppn,
            'judul_header1'     => $judul_header1,
            'nama'              => $nama,
            'alamat'            => $alamat,
            'tlp'               => $tlp,
            'email'             => $email,
            'website'           => $website,
            'gambar1'           => $gambar1,
            'gambar2'           => $gambar2,
        ];

        // dd($invo);

        // Return view dengan data yang disiapkan
        return view('validasi_usulan_belanja.keranjang_cek_usulan_belanja', $data, ['title' => 'Halaman Validasi', 'routePrefix' => 'validasi_usulan_barang_direkturs']);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id_usulan_barang)
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
            ->select('usulan_barangs.*', 'rkbus.*')
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
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->distinct() // Menghapus duplikasi berdasarkan sub_kategori_rkbus yang sama
            ->get();

        // Ambil detail invoice berdasarkan id_usulan_barang
        $invoice = UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)->first();
        $judul_headers = JudulHeader::first();


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

        // Cek apakah invoice ditemukan
        if ($invoice) {
            $keranjang = UsulanBarangDetail::where('usulan_barang_details.id_usulan_barang', $invoice->id_usulan_barang)
                ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu') // Join ke tabel rkbus
                ->select('usulan_barang_details.*', 'rkbus.nama_barang', 'rkbus.spek') // Select kolom yang diinginkan dari rkbus
                ->get();
        } else {
            $keranjang = collect(); // Jika tidak ada, gunakan collection kosong
        }

        // dd($invoice);

        $gambar1 = $judul_headers->gambar1 ? asset('storage/uploads/' . basename($judul_headers->gambar1)) : asset('storage/uploads/foto.png');

        // Cek apakah gambar2 ada, jika tidak gunakan gambar alias
        $gambar2 = $judul_headers->gambar2 ? asset('storage/uploads/' . basename($judul_headers->gambar2)) : asset('storage/uploads/foto.png');

        // Siapkan data untuk dikirim ke view
        $data = [
            'no_inv'            => $invo,
            'invoice'           => $invoice,
            'keranjang'         => $keranjang,
            'get_total'         => $sum_total_anggaran_usulan_barang,
            'ppn'               => $sum_total_ppn,
            'judul_header1'     => $judul_header1,
            'nama'              => $nama,
            'alamat'            => $alamat,
            'tlp'               => $tlp,
            'email'             => $email,
            'website'           => $website,
            'gambar1'           => $gambar1,
            'gambar2'           => $gambar2,
            'routePrefix'       => 'validasi_usulan_barang_direkturs'
        ];

        // dd($data);

        return view('validasi_usulan_belanja.cek_detail_usulan_belanja', $data, compact('sub_kategori_rkbus', 'usulan_barangs', 'id_usulan', 'invoice'), ['routePrefix' => 'validasi_usulan_barang_direkturs']);
    }

    public function edit($id)
    {
        $rkbuBarangJasa             = UsulanBarangDetail::with('usulan_barang')->findOrFail($id);
        $usulan_barangs             = UsulanBarang::all();
        $rkbu                       = Rkbu::where('id_rkbu', $rkbuBarangJasa->id_rkbu)->first();
        $uraian_satu                = UraianSatu::all(); // Ambil uraian1
        $uraian_dua                 = UraianDua::all(); // Ambil uraian2

        // Ambil id_ksp, id_user, dan tahun_anggaran dari session
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = session('tahun_anggaran'); // Lebih konsisten menggunakan session helper
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi RKA

        // Kirim data ke view
        return view('usulan_belanja.edit', compact('rkbuBarangJasa', 'rkbu', 'uraian_satu', 'uraian_dua'), ['title' => '', 'routePrefix' => 'validasi_usulan_barang_direkturs']);
    }

    public function update(Request $request, $id)
    {

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
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
            ],
            [
                'vol_1_detail'                      => 'Vol 1 wajib diisi.',
                'vol_2_detail'                      => 'Vol 2 wajib diisi.',
                'satuan_2_detail'                   => 'Satuan 2 wajib diisi.',
                'spek_detail'                       => 'Spesifikasi wajib diisi.',
                'satuan_1_detail.required'          => 'Satuan 1 wajib diisi.',
                'harga_barang.required'             => 'Harga satuan wajib diisi dan harus berupa angka.',
                'ppn.required'                      => 'PPN wajib diisi dan harus berupa angka antara 0 hingga 12.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }
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

            return redirect()->route('validasi_usulan_barang_direkturs.keranjang', $usulanBarangDetail->id_usulan_barang)
                ->with('success', 'Detail usulan barang berhasil diperbarui.');
        } else {
            // Jika total anggaran setelah update melebihi, tampilkan alert
            return redirect()->back()->with('lebih', 'Total Usulan melebihi Pagu Anggaran.');
        }
    }

    public function destroy(UsulanBarang $usulanBarang)
    {
        UsulanBarangDetail::where('id_usulan_barang', $usulanBarang->id_usulan_barang)->delete();
        $usulanBarang->delete();

        return redirect()->route('validasi_usulan_barang_direkturs.index')->with('success', 'RKBU Barang Jasa dan detailnya berhasil dihapus.');
    }

    public function doDelete($id_usulan_barang)
    {
        // Ambil data usulan barang berdasarkan id_usulan_barang
        $usulanBarang = UsulanBarang::find($id_usulan_barang);

        if (!$usulanBarang) {
            return redirect()->back()->with('error', 'Usulan barang tidak ditemukan.');
        }

        // Hapus data usulan barang dari database
        $usulanBarang->delete();

        // Redirect ke halaman belanja dengan pesan sukses
        return redirect()->route('validasi_usulan_barang_direkturs.index')->with('success', 'Data usulan barang berhasil dihapus.');
    }
}
