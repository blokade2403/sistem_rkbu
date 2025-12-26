<?php

namespace App\Http\Controllers\Rkbu\Administrator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RkbuHistory\RkbuHistory;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\DataTables\Administrator\RkbuPersediaanAdminDataTable;

class RkbuPersediaanAdminController extends Controller
{
    public function index(RkbuPersediaanAdminDataTable $dataTable, Request $request)
    {
        $id_pejabat          = session('id_pejabat');
        $id_user             = session('id_user');
        $status_validasi_rka = StatusValidasiRka::all();
        $tahunAnggaran       = session('tahun_anggaran');
        $daftarSumberDana = SumberDana::all();

        $id_tahun_anggaran   = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('id');
        $id_fase = session('id_fase');

        $tanggal_perencanaans = DB::table('tanggal_perencanaans')
            ->join('tahun_anggarans', 'tanggal_perencanaans.id_tahun_anggaran', '=', 'tahun_anggarans.id')
            ->where('tanggal_perencanaans.id_tahun_anggaran', $id_tahun_anggaran)
            ->where('tanggal_perencanaans.id_fase', $id_fase)
            ->select('tanggal_perencanaans.*')
            ->get();

        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3',
            '9cdfced0-87ce-49dc-8268-25ed56420bf7',
            '9cf6ed93-0b31-4941-a31a-82d07eb81873'
        ];

        $idKodeRekeningBelanja_blud  = '9cf603bb-bfd0-4b1e-8a24-7339459d9507';
        $idSumberDana           = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

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

        $total_anggaran_barjas_admin = $query_admin($idKodeRekeningBelanja_blud);
        $selisih = $jumlah_anggaran - $total_anggaran_barjas_admin;
        $currentSumberDana = $request->input('sumber_dana', '');
        $currentJenisKategori = $request->input('jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c');

        $sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu',  $currentJenisKategori)
            ->select('sub_kategori_rkbus.*')
            ->distinct()
            ->get();

        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->where('sumber_danas.id_sumber_dana', '=', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu',  $currentJenisKategori);

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->filled('sub_kategori_rkbu')) {
            $query->where('rkbus.id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->filled('id_status_validasi_rka')) {
            $query->where('rkbus.id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        if ($request->filled('sumber_dana')) {
            $query->where('sumber_danas.id_sumber_dana', $currentSumberDana);
        }

        $rkbus = $query->select('rkbus.*')->get();

        $total_anggaran = $rkbus->sum('total_anggaran');

        return $dataTable->render('rkbu.formatform_serverside.index', [
            'status_validasi_rka'           => $status_validasi_rka,
            'currentJenisKategori'          => $currentJenisKategori,
            'sub_kategori_rkbus'            => $sub_kategori_rkbus,
            'selisih'                       => $selisih,
            'total_anggaran'                => $total_anggaran,
            'jumlah_anggaran'               => $jumlah_anggaran,
            'total_anggaran_barjas_admin'   => $total_anggaran_barjas_admin,
            'daftarSumberDana'              => $daftarSumberDana,
            'currentSumberDana'             => $currentSumberDana
        ], ['title' => 'Halaman RKBU', 'routePrefix' => 'rkbu_persediaan_admins', 'routePrefixPrint' => 'rkbu_persediaan_admins']);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}

    public function edit(string $id)
    {
        $rkbuBarangJasa = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'status_validasi_rka' // Memastikan relasi status_validasi juga dipanggil
        ])->findOrFail($id);

        $nama_tahun_anggaran = session('tahun_anggaran');

        $validasiRkbuBarjasKsp = Rkbu::find($id);

        // Ambil nilai total anggaran sebelumnya
        $total_anggaran_sebelumnya = $validasiRkbuBarjasKsp->total_anggaran;
        $sisa_anggaran_sebelumnya = $validasiRkbuBarjasKsp->sisa_anggaran_rkbu;

        // Menghilangkan karakter non-digit dan menyisakan hanya angka
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;

        $user = User::join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->where('id_level', '9cff8aee-6b6d-49e8-a1d4-cb44f96377d8')
            ->select('users.*')
            ->get();

        $komponens              = Komponen::all();
        $faseTahun              = Fase::all();
        $sub_kategori_rkbus     = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->get();

        $id_kode_rekening_belanja   = '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c';

        $uraian_satu                = UraianSatu::all();
        $uraian_dua                 = UraianDua::all();
        $status_validasi_rka        = StatusValidasiRka::all();
        $status_validasi            = StatusValidasi::all();
        $rekening_belanja           = RekeningBelanja::all();

        return view('rkbu.formatform_persediaan.edit', compact('rkbuBarangJasa', 'total_anggaran_sebelumnya', 'sisa_anggaran_sebelumnya', 'faseTahun', 'rekening_belanja', 'status_validasi_rka', 'user', 'status_validasi', 'komponens', 'sub_kategori_rkbus', 'uraian_satu', 'uraian_dua', 'id_kode_rekening_belanja', 'angka_tahun', 'angka_kurang_2', 'angka_kurang_1'), ['title' => 'Halaman RKBU', 'routePrefix' => 'rkbu_persediaan_admins']);
    }

    public function massValidasi(Request $request)
    {
        $tahunAnggaran = Session::get('tahun_anggaran');
        $currentJenisKategori = $request->input('jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $id_pejabat          = session('id_pejabat');

        // Ambil semua id_rkbu yang ada di database
        $allRkbuIds = Rkbu::join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            // ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('id_status_validasi', '9cfb1edc-2263-401f-b249-361db4017932')
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', $currentJenisKategori)
            ->pluck('id_rkbu') // Pindahkan pluck setelah join dan where
            ->toArray();

        // Ambil id_rkbu yang dikirim (yang dicentang)
        $checkedIds = $request->input('id_rkbu', []); // Default sebagai array kosong jika tidak ada yang dicentang

        // Cek jika ada yang dicentang (checked)
        if ($checkedIds) {
            // Update untuk item yang dicentang
            Rkbu::whereIn('id_rkbu', $checkedIds)
                ->update(['id_status_validasi_rka' => '9cfb1f87-238b-4ea2-98f0-4255e578b1d1']);
        }

        // Cari id_rkbu yang tidak dicentang (uncheck), yaitu item yang ada di $allRkbuIds tapi tidak ada di $checkedIds
        $uncheckedIds = array_diff($allRkbuIds, $checkedIds);

        // Update untuk item yang di-uncheck
        if (!empty($uncheckedIds)) {
            Rkbu::whereIn('id_rkbu', $uncheckedIds)
                ->update(['id_status_validasi_rka' => '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34']);
        }

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Validasi berhasil diperbarui.');
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
        $nama_fase              = session('nama_fase');
        $faseTahun              = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('fase_tahun');

        $idKodeRekeningBelanja_blud = '9cf603bb-bfd0-4b1e-8a24-7339459d9507';
        $idSumberDana = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

        $jumlah_anggaran = Anggaran::where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_sumber_dana', $idSumberDana)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->value('jumlah_anggaran') ?? 0;

        $total_anggaran_barjas_admin  = Rkbu::where('nama_tahun_anggaran', $tahunAnggaran)
            ->where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->sum('total_anggaran');

        // Temukan data berdasarkan ID
        $validasiRkbuBarjasKsp = Rkbu::find($id);

        if (!$validasiRkbuBarjasKsp) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil nilai total anggaran sebelumnya
        $total_anggaran_sebelumnya = $validasiRkbuBarjasKsp->total_anggaran;

        // Hitung total anggaran baru
        $vol_1 = $request->input('rencana_pengadaan_tahun_x1');
        $harga_satuan = (int) str_replace('.', '', $request->input('harga_satuan'));
        $ppn = $request->input('ppn', 0);

        $jumlahVol = $vol_1;
        $total_anggaran = ($jumlahVol * $harga_satuan) + ($ppn / 100 * ($jumlahVol * $harga_satuan));

        // Hitung perubahan anggaran (selisih)
        $perubahan_anggaran = $total_anggaran - $total_anggaran_sebelumnya;

        // Cek perubahan status id_status_validasi_rka
        $new_status_validasi_rka = $request->input('id_status_validasi_rka');
        $old_status_validasi_rka = $validasiRkbuBarjasKsp->id_status_validasi_rka;

        if ($new_status_validasi_rka === '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' && $old_status_validasi_rka !== '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') {
            // Status berubah menjadi '9cfb1f87-238b-4ea2-98f0-4255e578b1d1', tambahkan anggaran
            $perubahan_anggaran += $total_anggaran_sebelumnya;
        } elseif ($new_status_validasi_rka !== '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' && $old_status_validasi_rka === '9cfb1f87-238b-4ea2-98f0-4255e578b1d1') {
            // Status berubah dari '9cfb1f87-238b-4ea2-98f0-4255e578b1d1', kurangi anggaran
            $perubahan_anggaran -= $total_anggaran_sebelumnya;
        }

        // Validasi input
        $validatedData = $request->validate([
            'id_sub_kategori_rkbu'          => 'required',
            'id_kode_rekening_belanja'      => '',
            'barang'                        => 'nullable',
            'nama_barang'                   => 'nullable',
            'stok'                           => 'required',
            'rata_rata_pemakaian'            => 'required',
            'kebutuhan_per_bulan'            => 'required',
            'buffer'                         => 'required',
            'pengadaan_sebelumnya'           => 'required',
            'proyeksi_sisa_stok'             => 'required',
            'kebutuhan_plus_buffer'          => 'required',
            'kebutuhan_tahun_x1'             => 'required',
            'rencana_pengadaan_tahun_x1'     => 'required',
            'satuan_1'                      => 'required',
            'id_status_validasi_rka'        => '',
            'spek'                          => 'nullable',
            'id_user'                       => 'nullable',
            'harga_satuan'                  => 'required|numeric',
            'ppn'                           => 'required|numeric',
            'rating'                        => 'nullable',
            'link_ekatalog'                 => 'nullable',
            'upload_file_1'                  => 'nullable|mimes:pdf',
            'upload_file_2'                  => 'nullable|mimes:pdf',
            'upload_file_3'                  => 'nullable|mimes:pdf',
            'upload_file_4'                  => 'nullable|mimes:pdf',
        ]);

        // Temukan data berdasarkan ID
        $validasiRkbuBarjasKsp = Rkbu::find($id);
        $data_sebelum = $validasiRkbuBarjasKsp->toArray();
        // dd($data_sebelum);

        // Pastikan data ditemukan sebelum update
        if (!$validasiRkbuBarjasKsp) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Mengatur file upload hanya jika file ada
        $validasiRkbuBarjasKsp->upload_file_1 = $request->hasFile('upload_file_1') ? $request->file('upload_file_1')->store('public/uploads') : $validasiRkbuBarjasKsp->upload_file_1;
        $validasiRkbuBarjasKsp->upload_file_2 = $request->hasFile('upload_file_2') ? $request->file('upload_file_2')->store('public/uploads') : $validasiRkbuBarjasKsp->upload_file_2;
        $validasiRkbuBarjasKsp->upload_file_3 = $request->hasFile('upload_file_3') ? $request->file('upload_file_3')->store('public/uploads') : $validasiRkbuBarjasKsp->upload_file_3;
        $validasiRkbuBarjasKsp->upload_file_4 = $request->hasFile('upload_file_4') ? $request->file('upload_file_4')->store('public/uploads') : $validasiRkbuBarjasKsp->upload_file_4;

        $namaFile6 = $request->hasFile('upload_file_5') ? $request->file('upload_file_5')->store('public/uploads') : null; // Tambahan untuk file 5

        $barang = $request->input('nama_barang') ?? $request->input('barang');

        // Menghitung total anggaran dan volume
        $rencana_pengadaan_tahun_x1 = $request->input('rencana_pengadaan_tahun_x1');
        $harga_satuan = (int) str_replace('.', '', $request->input('harga_satuan'));
        $ppn = $request->input('ppn', 0);

        $jumlahVol = $rencana_pengadaan_tahun_x1;
        $totalAnggaran = ($rencana_pengadaan_tahun_x1 * $harga_satuan) + ($ppn / 100 * ($rencana_pengadaan_tahun_x1 * $harga_satuan));
        $sisa_vol_rkbu = $jumlahVol;
        $sisa_anggaran_rkbu = $totalAnggaran;

        $idKodeRekeningBelanja  = '9cf603bb-bfd0-4b1e-8a24-7339459d9507';

        // Update field secara manual tanpa mass assignment
        $validasiRkbuBarjasKsp->id_sub_kategori_rekening    = $request->input('id_sub_kategori_rekening');
        $validasiRkbuBarjasKsp->id_sub_kategori_rkbu        = $request->input('id_sub_kategori_rkbu');
        $validasiRkbuBarjasKsp->id_kode_rekening_belanja    = $request->input('id_kode_rekening_belanja');
        $validasiRkbuBarjasKsp->nama_barang                 = $barang;
        $validasiRkbuBarjasKsp->satuan_1                    = $request->input('satuan_1');
        $validasiRkbuBarjasKsp->stok                        = $request->input('stok');
        $validasiRkbuBarjasKsp->rata_rata_pemakaian         = $request->input('rata_rata_pemakaian');
        $validasiRkbuBarjasKsp->kebutuhan_per_bulan         = $request->input('kebutuhan_per_bulan');
        $validasiRkbuBarjasKsp->buffer                      = $request->input('buffer');
        $validasiRkbuBarjasKsp->pengadaan_sebelumnya        = $request->input('pengadaan_sebelumnya');
        $validasiRkbuBarjasKsp->proyeksi_sisa_stok          = $request->input('proyeksi_sisa_stok');
        $validasiRkbuBarjasKsp->kebutuhan_plus_buffer       = $request->input('kebutuhan_plus_buffer');
        $validasiRkbuBarjasKsp->kebutuhan_tahun_x1          = $request->input('kebutuhan_tahun_x1');
        $validasiRkbuBarjasKsp->rencana_pengadaan_tahun_x1  = $request->input('rencana_pengadaan_tahun_x1');
        $validasiRkbuBarjasKsp->jumlah_vol                  = $request->input('rencana_pengadaan_tahun_x1');
        $validasiRkbuBarjasKsp->harga_satuan                = $request->input('harga_satuan');
        $validasiRkbuBarjasKsp->ppn                         = $request->input('ppn');
        $validasiRkbuBarjasKsp->total_anggaran              = $request->input('total_anggaran');
        $validasiRkbuBarjasKsp->spek                        = $request->input('spek');
        $validasiRkbuBarjasKsp->id_user                     = $request->input('id_user');
        $validasiRkbuBarjasKsp->rating                      = $request->input('rating');
        $validasiRkbuBarjasKsp->link_ekatalog               = $request->input('link_ekatalog');
        $validasiRkbuBarjasKsp->total_anggaran              = $totalAnggaran;
        $validasiRkbuBarjasKsp->id_status_validasi_rka      = $request->input('id_status_validasi_rka');
        $validasiRkbuBarjasKsp->id_status_validasi          = $request->input('id_status_validasi');
        $validasiRkbuBarjasKsp->sisa_vol_rkbu               = $sisa_vol_rkbu;
        $validasiRkbuBarjasKsp->sisa_anggaran_rkbu          = $sisa_anggaran_rkbu;

        $dataSesudah = [
            'id_sub_kategori_rekening'    => $request->input('id_sub_kategori_rekening'),
            'id_sub_kategori_rkbu'        => $request->input('id_sub_kategori_rkbu'),
            'id_sub_kegiatan'             => $request->input('id_sub_kegiatan'),
            'id_status_validasi'          => $request->input('id_status_validasi'),
            'id_status_validasi_rka'      => $request->input('id_status_validasi_rka'),
            'id_kode_rekening_belanja'    => $request->input('id_kode_rekening_belanja'),
            'id_user'                     => $request->input('id_user'),
            'nama_barang'                 => $request->input('nama_barang'),
            'satuan_1'                    => $request->input('satuan_1'),
            'spek'                        => $request->input('spek'),
            'jumlah_vol'                  => $jumlahVol,
            'harga_satuan'                => $harga_satuan,
            'ppn'                         => $ppn,
            'total_anggaran'              => $totalAnggaran,
            'rating'                      => $request->input('rating'),
            'nama_tahun_anggaran'         => $nama_tahun_anggaran,
            'link_ekatalog'               => $request->input('link_ekatalog'),
            'upload_file_1'                 => $validasiRkbuBarjasKsp->upload_file_1,
            'upload_file_2'                 => $validasiRkbuBarjasKsp->upload_file_2,
            'upload_file_3'                 => $validasiRkbuBarjasKsp->upload_file_3,
            'upload_file_4'                 => $validasiRkbuBarjasKsp->upload_file_4,
            'keterangan_status'           => $request->input('keterangan_status'),
            'penempatan'                  => $request->input('penempatan'),
            'sisa_vol_rkbu'               => $jumlahVol,
            'sisa_anggaran_rkbu'          => $totalAnggaran,
            'stok'                        => $request->input('stok'),
            'rata_rata_pemakaian'         => $request->input('rata_rata_pemakaian'),
            'kebutuhan_per_bulan'         => $request->input('kebutuhan_per_bulan'),
            'buffer'                      => $request->input('buffer'),
            'pengadaan_sebelumnya'        => $request->input('pengadaan_sebelumnya'),
            'proyeksi_sisa_stok'          => $request->input('proyeksi_sisa_stok'),
            'kebutuhan_plus_buffer'       => $request->input('kebutuhan_plus_buffer'),
            'kebutuhan_tahun_x1'          => $request->input('kebutuhan_tahun_x1'),
            'rencana_pengadaan_tahun_x1'  => $request->input('rencana_pengadaan_tahun_x1'),
            'nama_pegawai'                => $request->input('nama_pegawai'),
            'tempat_lahir'                => $request->input('tempat_lahir'),
            'tanggal_lahir'               => $request->input('tanggal_lahir'),
            'pendidikan'                  => $request->input('pendidikan'),
            'status_kawin'                => $request->input('status_kawin'),
            'nomor_kontrak'               => $request->input('nomor_kontrak'),
            'tmt_pegawai'                 => $request->input('tmt_pegawai'),
            'bulan_tmt'                   => $request->input('bulan_tmt'),
            'gaji_pokok'                  => $request->input('gaji_pokok'),
            'remunerasi'                  => $request->input('remunerasi'),
            'koefisien_remunerasi'        => $request->input('koefisien_remunerasi'),
            'koefisien_gaji'              => $request->input('koefisien_gaji'),
            'bpjs_kesehatan'              => $request->input('bpjs_kesehatan'),
            'bpjs_tk'                     => $request->input('bpjs_tk'),
            'bpjs_jht'                    => $request->input('bpjs_jht'),
            'total_gaji_pokok'            => $request->input('total_gaji_pokok'),
            'total_remunerasi'            => $request->input('total_remunerasi'),
            'status_komponen'             => $request->input('status_komponen'),
            'standar_kebutuhan'           => $request->input('standar_kebutuhan'),
            'eksisting'                   => $request->input('eksisting'),
            'kondisi_baik'                => $request->input('kondisi_baik'),
            'kondisi_rusak_berat'         => $request->input('kondisi_rusak_berat'),
            'created_at'                  => now(),
        ];

        if (!in_array($faseTahun, ['Perencanaan', 'Perubahan'])) {
            RkbuHistory::create([
                'id_rkbu'                   => $validasiRkbuBarjasKsp->id_rkbu,
                'id_jenis_kategori_rkbu'    => '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c',
                'id_user'                   => $id_user,
                'data_sebelum'              => json_encode($data_sebelum),
                'data_sesudah'              => json_encode($dataSesudah), // Simpan data sesudah
                'keterangan_status'         => $request->input('keterangan_status'),
                'upload_file_5'             => $namaFile6 ?? null,
            ]);

            // Validasi anggaran
            $toleransi = 0.5;
            $totalSetelahUpdate = $total_anggaran_barjas_admin + $perubahan_anggaran;
            // dd($totalSetelahUpdate - $jumlah_anggaran, $toleransi);

            // Jika melebihi jumlah anggaran lebih dari toleransi, tolak
            if ($totalSetelahUpdate - $jumlah_anggaran > $toleransi) {
                return redirect()->back()->with('error', 'Tidak bisa Update Anggaran melebihi Pagu.');
            }

            /* ---------------------------- */
            $id_rkbu = $validasiRkbuBarjasKsp->id_rkbu;
            $totalAnggaranUsulan = DB::table('usulan_barang_details')
                ->where('id_rkbu', $id_rkbu)
                ->sum('total_anggaran_usulan_barang');

            $total_anggaran_usulan_baru = $jumlahVol * $harga_satuan + ($ppn / 100 * ($jumlahVol * $harga_satuan));

            // Ambil total anggaran usulan lama
            $totalAnggaranLama = DB::table('usulan_barang_details')
                ->where('id_rkbu', $id_rkbu)
                ->sum('total_anggaran_usulan_barang');

            // Hitung sisa anggaran RKBU setelah penambahan usulan baru
            $sisa_anggaran_baru = round($total_anggaran_usulan_baru - $totalAnggaranLama, 2);
            // // dd($total_anggaran_usulan_baru, $totalAnggaranLama, $sisa_anggaran_sebelumnya, $total_anggaran_sebelumnya);

            // Jika sisa anggaran < -0.5, jangan simpan dan beri alert
            if ($sisa_anggaran_baru < -0.5) {
                return redirect()->back()->with('error', 'Sisa anggaran tidak mencukupi! Pagu anggaran akan menjadi minus.');
            }
            /* ---------------------------- */
        }

        if (isset($upload_file_1)) {
            $validasiRkbuBarjasKsp->upload_file_1 = $upload_file_1;
        }
        if (isset($upload_file_2)) {
            $validasiRkbuBarjasKsp->upload_file_2 = $upload_file_2;
        }
        if (isset($upload_file_3)) {
            $validasiRkbuBarjasKsp->upload_file_3 = $upload_file_3;
        }
        if (isset($upload_file_4)) {
            $validasiRkbuBarjasKsp->upload_file_4 = $upload_file_4;
        }

        // Update data
        $validasiRkbuBarjasKsp->save();

        // Hitung akumulasi usulan_barang_details.jumlah_usulan_barang dan total_anggaran_usulan_barang
        $id_rkbu = $validasiRkbuBarjasKsp->id_rkbu;

        $totalJumlahUsulan = DB::table('usulan_barang_details')
            ->where('id_rkbu', $id_rkbu)
            ->sum('jumlah_usulan_barang');

        $totalAnggaranUsulan = DB::table('usulan_barang_details')
            ->where('id_rkbu', $id_rkbu)
            ->sum('total_anggaran_usulan_barang');

        // Update sisa_vol_rkbu dan sisa_anggaran_rkbu pada tabel rkbus
        DB::table('rkbus')
            ->where('id_rkbu', $id_rkbu)
            ->update([
                'sisa_vol_rkbu' => $jumlahVol - $totalJumlahUsulan,
                'sisa_anggaran_rkbu' => $totalAnggaran - $totalAnggaranUsulan
            ]);

        // Redirect dengan pesan sukses
        return redirect()->route('rkbu_persediaan_admins.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Temukan data berdasarkan ID
        $rkbu = Rkbu::find($id);

        // Periksa apakah data ditemukan
        if (!$rkbu) {
            return redirect()->route('rkbu_persediaan_admins.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        try {
            // Hapus data
            $rkbu->delete();

            return redirect()->route('rkbu_persediaan_admins.index')
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error
            return redirect()->route('rkbu_persediaan_admins.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
