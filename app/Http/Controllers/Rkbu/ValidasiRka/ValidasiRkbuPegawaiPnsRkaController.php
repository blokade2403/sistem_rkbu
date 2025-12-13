<?php

namespace App\Http\Controllers\Rkbu\ValidasiRka;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\UserProfil\Pejabat;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class ValidasiRkbuPegawaiPnsRkaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil id_ksp dari session
        // Ambil id_ksp dari session
        $id_pejabat          = session('id_pejabat');
        $id_user             = session('id_user');
        $tahunAnggaran       = Session::get('tahun_anggaran');
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait
        $faseTahun           = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('fase_tahun');

        // Ambil data pejabat sesuai user login
        $pejabat = Pejabat::where('id_pejabat', $id_pejabat)
            ->where('status', 'aktif')
            ->first();
        $id_jabatan = $pejabat->id_jabatan;

        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3', // BLUD
            '9cdfced0-87ce-49dc-8268-25ed56420bf7', // APBD
            '9cf6ed93-0b31-4941-a31a-82d07eb81873' // DAK
        ];

        $id_kode_rekening_belanja_non_barjas_non_blud = [
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas_non_blud = [
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        $idKodeRekeningBelanja_blud     = '9cf6040a-2759-4d16-a3cf-3eee5194a2d5';
        $idSumberDana                   = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

        $anggaran = Anggaran::where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_sumber_dana', $idSumberDana)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->select('jumlah_anggaran')
            ->first();

        $jumlah_anggaran = $anggaran ? $anggaran->jumlah_anggaran : 0;

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

        $total_anggaran_pegawai_admin  = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin  = $query_admin('9cf603e2-e748-49f0-949f-6c3c30d42c3e');
        $total_anggaran_barjas_admin = $query_admin($idKodeRekeningBelanja_blud);
        $total_anggaran_admin = $total_anggaran_pegawai_admin + $total_anggaran_modal_admin + $total_anggaran_barjas_admin;

        $selisih = $jumlah_anggaran - $total_anggaran_barjas_admin;

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->where('pejabats.id_jabatan', $id_jabatan)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
            ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '9cf7e481-2dba-4482-bd27-bd0face739ed')
            ->select('sub_kategori_rkbus.*') // Pilih data sub_kategori_rkbu
            ->distinct() // Hilangkan duplikasi
            ->get();

        // Query untuk mengambil data rkbu
        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->where('pejabats.id_jabatan', $id_jabatan)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
            ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', '9cf7e481-2dba-4482-bd27-bd0face739ed')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d');


        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->filled('sub_kategori_rkbu')) {
            $query->where('rkbus.id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->filled('id_status_validasi_rka')) {
            $query->where('rkbus.id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        // Dapatkan data rkbu setelah filter
        $rkbus = $query->select('rkbus.*')->get();

        // Hitung total anggaran dari data yang diambil
        $total_anggaran = $rkbus->sum('total_anggaran');

        return view('rkbu.formatform_pegawai.index', compact(
            'rkbus',
            'total_anggaran',
            'sub_kategori_rkbus',
            'status_validasi_rka',
            'selisih',
            'jumlah_anggaran',
            'total_anggaran_barjas_admin',
        ), ['title' => 'Halaman RKBU', 'routePrefix' => 'validasi_rka_pegawai_pnss']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $rkbuBarangJasa = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'status_validasi' // Memastikan relasi status_validasi juga dipanggil
        ])->findOrFail($id);

        $komponens              = Komponen::all();
        $sub_kategori_rkbus     = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd329-6524-4d3f-aca5-93eade93046d')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->get();

        $uraian_satu                = UraianSatu::all();
        $uraian_dua                 = UraianDua::all();
        $status_validasi_rka        = StatusValidasiRka::all();

        return view('rkbu.formatform_pegawai.edit', compact('rkbuBarangJasa', 'status_validasi_rka', 'komponens', 'sub_kategori_rkbus', 'uraian_satu', 'uraian_dua'), ['title' => 'Halaman RKBU', 'routePrefix' => 'validasi_rka_pegawai_pnss']);
    }

    public function update(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Ambil data dari session
        $id_user                = session('id_user');
        $nama_tahun_anggaran    = session('tahun_anggaran');
        $nama_fase              = session('nama_fase');
        $tahunAnggaran          = Session::get('tahun_anggaran');
        $tahunAnggaran          = Session::get('tahun_anggaran');
        $id_pejabat             = session('id_pejabat');
        $faseTahun              = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('fase_tahun');

        $idKodeRekeningBelanja_blud = '9cf6040a-2759-4d16-a3cf-3eee5194a2d5';
        $idSumberDana               = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

        $jumlah_anggaran = Anggaran::where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_sumber_dana', $idSumberDana)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->value('jumlah_anggaran') ?? 0;

        $total_anggaran_barjas_admin  = Rkbu::where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->sum('total_anggaran');

        // Temukan data berdasarkan ID
        $RkbuPegawai = Rkbu::find($id);

        if (!$RkbuPegawai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil nilai total anggaran sebelumnya
        $total_anggaran_sebelumnya = $RkbuPegawai->total_anggaran;

        $gaji_pokok                     = $request->input('gaji_pokok');
        $koefisien_gaji                 = $request->input('koefisien_gaji');
        $remunerasi                     = $request->input('remunerasi');
        $koefisien_remunerasi           = $request->input('koefisien_remunerasi');
        $koefisien_gaji                 = $request->input('koefisien_gaji');
        $bpjs_kesehatan                 = $request->input('bpjs_kesehatan');
        $bpjs_tk                        = $request->input('bpjs_tk');
        $bpjs_jht                       = $request->input('bpjs_jht');
        $total_gaji_pokok               = $gaji_pokok * $koefisien_gaji;
        $total_remunerasi               = $remunerasi * $koefisien_remunerasi;


        // Hitung total anggaran baru
        $total_anggaran = $total_remunerasi + $total_remunerasi;

        // Hitung perubahan anggaran (selisih)
        $perubahan_anggaran = $total_anggaran - $total_anggaran_sebelumnya;

        // Cek perubahan status id_status_validasi_rka
        $new_status_validasi_rka = $request->input('id_status_validasi_rka');
        $old_status_validasi_rka = $RkbuPegawai->id_status_validasi_rka;

        if ($new_status_validasi_rka === '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' && $old_status_validasi_rka !== '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') {
            // Status berubah menjadi '9cfb1f87-238b-4ea2-98f0-4255e578b1d1', tambahkan anggaran
            $perubahan_anggaran += $total_anggaran_sebelumnya;
        } elseif ($new_status_validasi_rka !== '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' && $old_status_validasi_rka === '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') {
            // Status berubah dari '9cfb1f87-238b-4ea2-98f0-4255e578b1d1', kurangi anggaran
            $perubahan_anggaran -= $total_anggaran_sebelumnya;
        }

        // dd($total_anggaran_sebelumnya, $perubahan_anggaran, ($total_anggaran_barjas_admin + $perubahan_anggaran));

        // Validasi anggaran
        if (($total_anggaran_barjas_admin + $perubahan_anggaran) > $jumlah_anggaran) {
            return redirect()->back()->with('error', 'Tidak bisa Update Anggaran melebihi Pagu.');
        }

        // Validasi input
        $validatedData = $request->validate([
            'id_sub_kategori_rkbu'          => 'required',
            'id_kode_rekening_belanja'      => 'required',
            'id_user'                       => 'required',
            'total_anggaran'                => 'required',
            'nama_tahun_anggaran'           => 'required',
            'id_status_validasi_rka'            => '',
            'nama_pegawai'                   => 'nullable',
            'tempat_lahir'                   => 'nullable',
            'tanggal_lahir'                     => 'nullable',
            'pendidikan'                        => 'nullable',
            'status_kawin'                      => 'nullable',
            'nomor_kontrak'                     => 'nullable',
            'tmt_pegawai'                       => 'nullable',
            'bulan_tmt'                         => 'nullable',
            'tahun_tmt'                         => 'nullable',
            'gaji_pokok'                        => 'nullable',
            'remunerasi'                        => 'nullable',
            'koefisien_remunerasi'              => 'nullable',
            'koefisien_gaji'                    => 'nullable',
            'bpjs_kesehatan'                    => 'nullable',
            'bpjs_tk'                           => 'nullable',
            'total_gaji_pokok'                  => 'nullable',
            'total_remunerasi'                  => 'nullable',
        ]);

        // Temukan data berdasarkan ID
        $RkbuPegawai = Rkbu::find($id);

        // dd($validasiRkbuBarjasKsp);

        // Pastikan data ditemukan sebelum update
        if (!$RkbuPegawai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Tentukan nilai status_komponen berdasarkan session 'nama_fase'
        $status_komponen = null; // Default null
        if (session('nama_fase') == 'Penetapan') {
            $status_komponen = 'Komponen Baru';
        } elseif (session('nama_fase') == 'Perubahan') {
            $status_komponen = 'Komponen Perubahan';
        }


        // Ambil id_kode_rekening_belanja dari request
        $idKodeRekeningBelanja  = '9cf6040a-2759-4d16-a3cf-3eee5194a2d5'; // ID Belanja Modal Peralatan dan Mesin BLUD

        $gaji_pokok                     = $request->input('gaji_pokok');
        $koefisien_gaji                 = $request->input('koefisien_gaji');
        $remunerasi                     = $request->input('remunerasi');
        $koefisien_remunerasi           = $request->input('koefisien_remunerasi');
        $koefisien_gaji                 = $request->input('koefisien_gaji');
        $bpjs_kesehatan                 = $request->input('bpjs_kesehatan');
        $bpjs_tk                        = $request->input('bpjs_tk');
        $bpjs_jht                       = $request->input('bpjs_jht');
        $total_gaji_pokok               = $gaji_pokok * $koefisien_gaji;
        $total_remunerasi               = $remunerasi * $koefisien_remunerasi;
        $jumlah_vol                     = $koefisien_remunerasi;
        $sisa_vol_rkbu                  = $jumlah_vol;

        $RkbuPegawai->satuan_1                      = $request->input('satuan_1', 0);
        $RkbuPegawai->vol_2                         = $request->input('vol_2', 0);
        $RkbuPegawai->satuan_2                      = $request->input('satuan_2', 0);
        $RkbuPegawai->spek                          = $request->input('spek', 0);
        $RkbuPegawai->harga_satuan                  = $request->input('harga_satuan', 0);
        $RkbuPegawai->ppn                           = $request->input('ppn', 0);
        $RkbuPegawai->rating                        = $request->input('rating', 0);
        $RkbuPegawai->link_ekatalog                 = $request->input('link_ekatalog', 0);
        $RkbuPegawai->penempatan                    = $request->input('penempatan', 0);
        $RkbuPegawai->vol_1                           = $request->input('vol_1', 0);
        $RkbuPegawai->vol_1                           = $request->input('vol_1', 0);
        $RkbuPegawai->stok                           = $request->input('stok', 0);
        $RkbuPegawai->rata_rata_pemakaian            = $request->input('rata_rata_pemakaian', 0);
        $RkbuPegawai->kebutuhan_per_bulan            = $request->input('kebutuhan_per_bulan', 0);
        $RkbuPegawai->buffer                         = $request->input('buffer', 0);
        $RkbuPegawai->pengadaan_sebelumnya           = $request->input('pengadaan_sebelumnya', 0);
        $RkbuPegawai->proyeksi_sisa_stok             = $request->input('proyeksi_sisa_stok', 0);
        $RkbuPegawai->kebutuhan_plus_buffer          = $request->input('kebutuhan_plus_buffer', 0);
        $RkbuPegawai->kebutuhan_tahun_x1             = $request->input('kebutuhan_tahun_x1', 0);
        $RkbuPegawai->rencana_pengadaan_tahun_x1     = $request->input('rencana_pengadaan_tahun_x1', 0);
        $RkbuPegawai->id_sub_kategori_rekening    = $request->input('id_sub_kategori_rekening');
        $RkbuPegawai->id_sub_kategori_rkbu        = $request->input('id_sub_kategori_rkbu');
        $RkbuPegawai->id_kode_rekening_belanja    = $idKodeRekeningBelanja;
        $RkbuPegawai->nama_barang                 = $request->input('nama_barang', 0);
        $RkbuPegawai->nama_tahun_anggaran         = $nama_tahun_anggaran;
        $RkbuPegawai->id_status_validasi_rka          = $request->input('id_status_validasi_rka');
        $RkbuPegawai->jumlah_vol                  = $koefisien_remunerasi;
        $RkbuPegawai->nama_pegawai                = $request->input('nama_pegawai');
        $RkbuPegawai->tempat_lahir                = $request->input('tempat_lahir');
        $RkbuPegawai->tanggal_lahir               = $request->input('tanggal_lahir');
        $RkbuPegawai->jabatan                     = $request->input('jabatan');
        $RkbuPegawai->pendidikan                  = $request->input('pendidikan');
        $RkbuPegawai->status_kawin                = $request->input('status_kawin');
        $RkbuPegawai->nomor_kontrak               = $request->input('nomor_kontrak');
        $RkbuPegawai->tmt_pegawai                 = $request->input('tmt_pegawai');
        $RkbuPegawai->bulan_tmt                   = $request->input('bulan_tmt');
        $RkbuPegawai->tahun_tmt                   = $request->input('tahun_tmt');
        $RkbuPegawai->gaji_pokok                  = $request->input('gaji_pokok');
        $RkbuPegawai->remunerasi                  = $remunerasi;
        $RkbuPegawai->koefisien_remunerasi        = $koefisien_remunerasi;
        $RkbuPegawai->koefisien_gaji              = $koefisien_gaji;
        $RkbuPegawai->bpjs_kesehatan              = $bpjs_kesehatan;
        $RkbuPegawai->bpjs_tk                     = $bpjs_tk;
        $RkbuPegawai->bpjs_jht                    = $bpjs_jht;
        $RkbuPegawai->total_gaji_pokok            = $total_gaji_pokok;
        $RkbuPegawai->total_remunerasi            = $total_remunerasi;
        $RkbuPegawai->total_anggaran              = ($remunerasi * $koefisien_remunerasi) +
            (($gaji_pokok * ($bpjs_kesehatan / 100)) * $koefisien_gaji) +
            (($gaji_pokok * ($bpjs_tk / 100)) * $koefisien_gaji) +
            (($gaji_pokok * ($bpjs_jht / 100) * $koefisien_gaji)) + ($gaji_pokok * $koefisien_gaji);
        $RkbuPegawai->sisa_vol_rkbu              = $sisa_vol_rkbu;
        $RkbuPegawai->sisa_anggaran_rkbu         = ($remunerasi * $koefisien_remunerasi) +
            (($gaji_pokok * ($bpjs_kesehatan / 100)) * $koefisien_gaji) +
            (($gaji_pokok * ($bpjs_tk / 100)) * $koefisien_gaji) +
            (($gaji_pokok * ($bpjs_jht / 100) * $koefisien_gaji)) + ($gaji_pokok * $koefisien_gaji);
        $RkbuPegawai->status_komponen            = $status_komponen;

        // dd($rkbuModalKantor);

        // Simpan data
        $RkbuPegawai->save();

        // Redirect dengan pesan sukses
        return redirect()->route('validasi_rka_pegawai_pnss.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
