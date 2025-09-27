<?php

namespace App\Http\Controllers\Rkbu\ValidasiKsp;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class ValidasiRkbuPersediaanKspController extends Controller
{

    public function index(Request $request)
    {
        // Ambil id_ksp dari session
        $id_ksp = session('id_ksp');

        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait


        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::whereHas('kategoriRkbu.jenis_kategori_rkbu', function ($query) {
            $query->where(
                'id_jenis_kategori_rkbu',
                '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c'
            );
        })
            ->whereHas('rkbus.user.ksp', function ($query) use ($id_ksp) {
                $query->where('id_ksp', $id_ksp);
            })
            ->whereHas('rkbus', function ($query) use ($tahunAnggaran) {
                $query->where('nama_tahun_anggaran', $tahunAnggaran);
            })
            ->get();

        $nama_tahun_anggaran = session('tahun_anggaran');
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;

        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3',
            '9cdfced0-87ce-49dc-8268-25ed56420bf7',
            '9cf6ed93-0b31-4941-a31a-82d07eb81873'
        ];

        $idKodeRekeningBelanja_blud  = '9cf603bb-bfd0-4b1e-8a24-7339459d9507';
        $idSumberDana                = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

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

        $query = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c');

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

        return view('rkbu.formatform_persediaan.index', compact('rkbus', 'total_anggaran', 'sub_kategori_rkbus', 'status_validasi_rka', 'angka_kurang_1', 'angka_tahun', 'angka_kurang_2'), [
            'title' => 'Halaman RKBU',
            'routePrefix' => 'validasi_ksp_rkbu_persediaans',
            'sub_kategori_rkbus' => $sub_kategori_rkbus,
            'status_validasi_rka' => $status_validasi_rka,
            'selisih'               => $selisih
        ]);
    }


    public function create() {}


    public function store(Request $request) {}


    public function show(string $id) {}


    public function edit(string $id)
    {
        $rkbuBarangJasa = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'status_validasi' // Memastikan relasi status_validasi juga dipanggil
        ])->findOrFail($id);

        $nama_tahun_anggaran = session('tahun_anggaran');

        // Menghilangkan karakter non-digit dan menyisakan hanya angka
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;


        $komponens              = Komponen::all();
        $sub_kategori_rkbus     = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->get();

        $id_kode_rekening_belanja       = '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c';

        $uraian_satu                = UraianSatu::all();
        $uraian_dua                 = UraianDua::all();
        $status_validasi            = StatusValidasi::all();

        return view('rkbu.formatform_persediaan.edit', compact('rkbuBarangJasa', 'status_validasi', 'komponens', 'sub_kategori_rkbus', 'uraian_satu', 'uraian_dua', 'id_kode_rekening_belanja', 'angka_tahun', 'angka_kurang_2', 'angka_kurang_1'), [
            'title' => 'Halaman RKBU',
            'routePrefix' => 'validasi_ksp_rkbu_persediaans',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Ambil data dari session
        $id_user = session('id_user');
        $nama_tahun_anggaran = session('tahun_anggaran');

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
            'id_status_validasi'            => '',
            'spek'                          => 'nullable',
            'penempatan'                    => 'nullable',
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

        // dd($validasiRkbuBarjasKsp);

        // Pastikan data ditemukan sebelum update
        if (!$validasiRkbuBarjasKsp) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Mengatur file upload hanya jika file ada
        $namaFile1 = $request->hasFile('upload_file_1') ? $request->file('upload_file_1')->store('public/uploads') : null;
        $namaFile2 = $request->hasFile('upload_file_2') ? $request->file('upload_file_2')->store('public/uploads') : null;
        $namaFile3 = $request->hasFile('upload_file_3') ? $request->file('upload_file_3')->store('public/uploads') : null;
        $namaFile4 = $request->hasFile('upload_file_4') ? $request->file('upload_file_4')->store('public/uploads') : null;

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
        $validasiRkbuBarjasKsp->nama_barang                 = $barang;
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
        $validasiRkbuBarjasKsp->penempatan                  = $request->input('penempatan');
        $validasiRkbuBarjasKsp->total_anggaran              = $request->input('total_anggaran');
        $validasiRkbuBarjasKsp->spek                        = $request->input('spek');
        $validasiRkbuBarjasKsp->rating                      = $request->input('rating');
        $validasiRkbuBarjasKsp->link_ekatalog               = $request->input('link_ekatalog');
        $validasiRkbuBarjasKsp->total_anggaran              = $totalAnggaran;
        $validasiRkbuBarjasKsp->id_status_validasi          = $request->input('id_status_validasi');
        $validasiRkbuBarjasKsp->sisa_vol_rkbu               = $sisa_vol_rkbu;
        $validasiRkbuBarjasKsp->sisa_anggaran_rkbu          = $sisa_anggaran_rkbu;

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
                'sisa_vol_rkbu'         => $jumlahVol - $totalJumlahUsulan,
                'sisa_anggaran_rkbu'    => $totalAnggaran - $totalAnggaranUsulan
            ]);

        // Redirect dengan pesan sukses
        return redirect()->route('validasi_ksp_rkbu_persediaans.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari data berdasarkan ID
            $rkbu = Rkbu::findOrFail($id);

            // Kalau ada relasi ke detail, pastikan ikut dihapus (optional)
            DB::table('rkbus')->where('id_rkbu', $id)->delete();

            // Hapus record utama
            $rkbu->delete();

            return redirect()
                ->route('validasi_ksp_rkbu_persediaans.index')
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
