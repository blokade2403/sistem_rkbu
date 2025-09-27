<?php

namespace App\Http\Controllers\Rkbu;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\RkbuPersediaan;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class RkbuPersediaanController extends Controller
{

    public function index(Request $request)
    {
        // Ambil id_user dari session
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::whereHas('rkbus', function ($query) use ($id_user) {
            $query->where('id_user', $id_user)
                ->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                    $query->where('id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c');
                });
        })->get();

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

        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi dari model terkait

        // Ambil data rkbu berdasarkan filter sub_kategori_rkbu
        $query = Rkbu::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu',
        ])->where('id_user', $id_user) // Filter data berdasarkan id_user
            ->where('nama_tahun_anggaran', $tahunAnggaran)
            ->whereHas('subKategoriRkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu', function ($query) {
                $query->where('id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c');
            });

        // Cek apakah ada filter sub_kategori_rkbu yang dipilih
        if ($request->has('sub_kategori_rkbu') && $request->sub_kategori_rkbu != '') {
            $query->where('id_sub_kategori_rkbu', $request->sub_kategori_rkbu);
        }

        // Filter berdasarkan id_status_validasi_rka
        if ($request->has('id_status_validasi_rka') && $request->id_status_validasi_rka != '') {
            $query->where('id_status_validasi_rka', $request->id_status_validasi_rka);
        }

        $nama_tahun_anggaran = session('tahun_anggaran');

        // Menghilangkan karakter non-digit dan menyisakan hanya angka
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;

        // Dapatkan data rkbu
        $rkbus = $query->select('rkbus.*')->get();

        // Ambil total anggaran berdasarkan id_user dari session
        $total_anggaran = $rkbus->sum('total_anggaran');

        return view('rkbu.formatform_persediaan.index', compact('rkbus', 'selisih', 'sub_kategori_rkbus', 'status_validasi_rka', 'total_anggaran', 'angka_kurang_1', 'angka_tahun', 'angka_kurang_2'), ['title' => 'Halaman RKBU',  'routePrefix' => 'rkbu_persediaans',]);
    }

    public function create()
    {
        $komponens              = Komponen::all();
        $rkbuall                = Rkbu::all();
        $rkbuBarangJasa         = new Rkbu(); // objek kosong
        $faseTahun              = Fase::all();
        $sub_kategori_rkbus     = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd364-2946-4ea1-bec2-e1130e8d9f2c')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->get();



        $nama_tahun_anggaran = session('tahun_anggaran');

        // Menghilangkan karakter non-digit dan menyisakan hanya angka
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;

        $uraian_satu            = UraianSatu::all();
        $uraian_dua             = UraianDua::all();
        // dd($uraian_satus);
        return view('rkbu.formatform_persediaan.create', compact('rkbuall', 'faseTahun', 'rkbuBarangJasa', 'komponens', 'sub_kategori_rkbus', 'uraian_satu', 'uraian_dua', 'angka_tahun', 'angka_kurang_2', 'angka_kurang_1'), ['title' => 'Halaman RKBU',  'routePrefix' => 'rkbu_persediaans',]);
    }

    public function store(Request $request)
    {
        // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
        if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
            return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
        }

        // Ambil data dari session
        $id_user = session('id_user');
        $nama_tahun_anggaran = session('tahun_anggaran');

        // Tentukan nilai status_komponen berdasarkan session 'nama_fase'
        $tahunAnggaran       = Session::get('tahun_anggaran');
        $faseTahun           = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('fase_tahun');

        // Tentukan nilai status_komponen berdasarkan session 'nama_fase'
        $status_komponen = null; // Default null
        if ($faseTahun == 'Penetapan') {
            $status_komponen = 'Komponen Baru';
        } elseif ($faseTahun == 'Perubahan') {
            $status_komponen = 'Komponen Perubahan';
        }

        // Validasi input
        $validator  = Validator::make(
            $request->all(),
            [
                'id_sub_kategori_rkbu'          => 'required',
                'id_kode_rekening_belanja'      => 'nullable',
                'barang'                        => 'nullable|string|max:255|required_without_all:nama_barang',
                'nama_barang'                   => 'nullable|string|max:255|required_without_all:barang',
                'stok'                          => 'required|integer|min:0',
                'rata_rata_pemakaian'           => 'required|numeric|min:0',
                'kebutuhan_per_bulan'           => 'required|numeric|min:0',
                'buffer'                        => 'required|numeric|min:0',
                'pengadaan_sebelumnya'          => 'required|numeric|min:0',
                'proyeksi_sisa_stok'            => 'required|numeric|min:0',
                'kebutuhan_plus_buffer'         => 'required|numeric|min:0',
                'kebutuhan_tahun_x1'            => 'required|numeric|min:0',
                'rencana_pengadaan_tahun_x1'    => 'required|numeric|min:0',
                'satuan_1'                      => 'required|string|max:255',
                'id_status_validasi'            => 'nullable',
                'spek'                          => 'nullable|string',
                'harga_satuan'                  => 'required|numeric|min:0',
                'ppn'                           => 'required|numeric|min:0|max:100',
                'rating'                        => 'nullable',
                'link_ekatalog'                 => 'nullable|string',
                'upload_file_1'                 => 'nullable|mimes:pdf|max:2048',
                'upload_file_2'                 => 'nullable|mimes:pdf|max:2048',
                'upload_file_3'                 => 'nullable|mimes:pdf|max:2048',
                'upload_file_4'                 => 'nullable|mimes:pdf|max:2048',
            ],
            [
                // Custom error messages
                'stok.required'                => 'Stok wajib diisi.',
                'harga_satuan.required'        => 'Harga satuan wajib diisi dan harus berupa angka.',
                'barang.required_without_all'       => 'Kolom Barang atau Nama Barang wajib diisi.',
                'nama_barang.required_without_all'  => 'Kolom Barang atau Nama Barang wajib diisi.',
                'ppn.numeric'                  => 'PPN harus berupa angka.',
                'ppn.min'                      => 'PPN tidak boleh kurang dari 0.',
                'ppn.max'                      => 'PPN tidak boleh lebih dari 12.',
                'upload_file_1.mimes'          => 'File yang diunggah harus berformat PDF.',
                'upload_file_1.max'            => 'File PDF tidak boleh lebih dari 2MB.',
                'rata_rata_pemakaian'           => 'Rata-rata Pemakaian wajib diisi',
                'kebutuhan_per_bulan'           => 'Kebutuhan Per Bulan wajib diisi',
                'buffer'                        => 'Buffer wajib diisi',
                'pengadaan_sebelumnya'          => 'Pengadaan Sebelumnya wajib diisi',
                'satuan_1'                      => 'Satuan wajib diisi',
                'link_ekatalog'                 => 'Link Ekatalog belum diisi',
            ]
        );

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Prioritaskan 'nama_barang' (input manual), jika ada
        $barang = $request->input('nama_barang') ?? $request->input('barang');

        // Mengatur file upload hanya jika file ada
        $namaFile1 = $request->hasFile('upload_file_1') ? $request->file('upload_file_1')->store('public/uploads') : null;
        $namaFile2 = $request->hasFile('upload_file_2') ? $request->file('upload_file_2')->store('public/uploads') : null;
        $namaFile3 = $request->hasFile('upload_file_3') ? $request->file('upload_file_3')->store('public/uploads') : null;
        $namaFile4 = $request->hasFile('upload_file_4') ? $request->file('upload_file_4')->store('public/uploads') : null;


        // Menghitung total anggaran
        $vol1 = $request->input('vol_1');
        $vol2 = $request->input('vol_2', 1);
        $harga_satuan = (int) str_replace('.', '', $request->input('harga_satuan'));
        $ppn = $request->input('ppn', 0);
        $rencana_kebutuhan = $request->input('rencana_pengadaan_tahun_x1');

        $jumlahVol = $rencana_kebutuhan;
        $totalAnggaran = ($jumlahVol * $harga_satuan) + ($ppn / 100 * ($jumlahVol * $harga_satuan));
        $sisa_vol_rkbu = $jumlahVol;
        $sisa_anggaran_rkbu = $totalAnggaran;

        // Ambil id_kode_rekening_belanja dari request
        $idKodeRekeningBelanja  = '9cf603bb-bfd0-4b1e-8a24-7339459d9507';
        $idStatusValidasi       = '9cfb1f38-dc3f-436f-8c4a-ec4c4de2fcaf'; // ID Belanja Barang dan Jasa BLUD

        // Inisialisasi model Rkbu dan tentukan fillable dinamis
        $rkbuBarangJasa = new Rkbu();

        // Isi data sesuai request
        $rkbuBarangJasa->fill([
            'id_sub_kategori_rekening'      => $request->input('id_sub_kategori_rekening'),
            // 'id_kategori_rkbu'              => $request->input('id_kegiatan'),
            'id_sub_kategori_rkbu'          => $request->input('id_sub_kategori_rkbu'),
            'id_kode_rekening_belanja'      => $idKodeRekeningBelanja,
            'id_user'                       => $id_user,
            'nama_barang'                   => $barang,
            'vol_1'                         => $request->input('vol_1', 0),
            'satuan_1'                      => $request->input('satuan_1'),
            'id_status_validasi'            => $idStatusValidasi,
            'vol_2'                         => $request->input('vol_2', 0),
            'satuan_2'                      => $request->input('satuan_2', 0),
            'jumlah_vol'                    => $jumlahVol,
            'spek'                          => $request->input('spek'),
            'harga_satuan'                  => $harga_satuan,
            'ppn'                           => $ppn,
            'total_anggaran'                => $totalAnggaran,
            'rating'                        => $request->input('rating'),
            'link_ekatalog'                 => $request->input('link_ekatalog'),
            'nama_tahun_anggaran'           => $nama_tahun_anggaran,
            'penempatan'                    => $request->input('penempatan', 0),
            'upload_file_1'                  => $namaFile1,
            'upload_file_2'                  => $namaFile2,
            'upload_file_3'                  => $namaFile3,
            'upload_file_4'                  => $namaFile4,
            'id_status_validasi_rka'         => $request->input('id_status_validasi_rka', '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34'), // Berikan nilai default jika kosong
            'stok'                           => $request->input('stok'),
            'rata_rata_pemakaian'            => $request->input('rata_rata_pemakaian'),
            'kebutuhan_per_bulan'            => $request->input('kebutuhan_per_bulan'),
            'buffer'                         => $request->input('buffer'),
            'pengadaan_sebelumnya'           => $request->input('pengadaan_sebelumnya'),
            'proyeksi_sisa_stok'             => $request->input('proyeksi_sisa_stok'),
            'kebutuhan_plus_buffer'          => $request->input('kebutuhan_plus_buffer'),
            'kebutuhan_tahun_x1'             => $request->input('kebutuhan_tahun_x1'),
            'rencana_pengadaan_tahun_x1'     => $request->input('rencana_pengadaan_tahun_x1'),
            'nama_pegawai'                   => $request->input('nama_pegawai', 0),
            'tempat_lahir'                   => $request->input('tempat_lahir', 0),
            'tanggal_lahir'                     => $request->input('tanggal_lahir', 0),
            'pendidikan'                        => $request->input('pendidikan', 0),
            'jabatan'                        => $request->input('jabatan', 0),
            'status_kawin'                      => $request->input('status_kawin', 0),
            'nomor_kontrak'                     => $request->input('nomor_kontrak', 0),
            'tmt_pegawai'                       => $request->input('tmt_pegawai', 0),
            'bulan_tmt'                         => $request->input('bulan_tmt', 0),
            'tahun_tmt'                         => $request->input('tahun_tmt', 0),
            'gaji_pokok'                        => $request->input('gaji_pokok', 0),
            'remunerasi'                        => $request->input('remunerasi', 0),
            'koefisien_remunerasi'              => $request->input('koefisien_remunerasi', 0),
            'koefisien_gaji'                    => $request->input('koefisien_gaji', 0),
            'bpjs_kesehatan'                    => $request->input('bpjs_kesehatan', 0),
            'bpjs_tk'                           => $request->input('bpjs_tk', 0),
            'total_gaji_pokok'                  => $request->input('total_gaji_pokok', 0),
            'total_remunerasi'                  => $request->input('total_remunerasi', 0),
            'sisa_vol_rkbu'                     => $sisa_vol_rkbu,
            'sisa_anggaran_rkbu'                => $sisa_anggaran_rkbu,
            'status_komponen'                   => $status_komponen, // Menyimpan status komponen berdasarkan session 'nama_fase'

        ]);

        // dd($rkbuBarangJasa);

        // Simpan data
        $rkbuBarangJasa->save();

        // Redirect dengan pesan sukses
        return redirect()->route('rkbu_persediaans.index')->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rkbuBarangJasa = RkbuPersediaan::with([
            'rekening_belanjas.aktivitas.sub_kegiatan.sumber_dana',
            'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            'sub_kategori_rkbu.kategori_rkbu.obyek_belanja.jenis_kategori_rkbu',
        ])->findOrFail($id);

        $nama_tahun_anggaran = session('tahun_anggaran');

        // Menghilangkan karakter non-digit dan menyisakan hanya angka
        $angka_tahun = (int) preg_replace('/[^0-9]/', '', $nama_tahun_anggaran);

        $angka_kurang_2   = $angka_tahun - 2;
        $angka_kurang_1   = $angka_tahun - 1;

        $total_anggaran = $rkbuBarangJasa->total_anggaran;
        $ppn            = $total_anggaran * (($rkbuBarangJasa->ppn) / 100);
        $sub_total      = $total_anggaran - $ppn;

        // dd($rkbuBarangJasa);

        return view('frontend.partials.detail_rkbu_persediaan', compact('rkbuBarangJasa', 'ppn', 'sub_total', 'angka_tahun', 'angka_kurang_2', 'angka_kurang_1'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    { {
            $rkbuPersediaan = RkbuPersediaan::with([
                'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
                'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
            ])->findOrFail($id);

            $rkbuBarangJasa = Rkbu::with([
                'rekening_belanjas.aktivitas.sub_kegiatan.sumber_danas',
                'rekening_belanjas.aktivitas.sub_kegiatan.kegiatan.program',
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
            $uraian_satu                = UraianSatu::all();
            $uraian_dua                 = UraianDua::all();

            return view('rkbu.formatform_persediaan.edit', compact('rkbuBarangJasa', 'rkbuPersediaan', 'komponens', 'sub_kategori_rkbus', 'uraian_satu', 'uraian_dua', 'angka_tahun', 'angka_kurang_2', 'angka_kurang_1'),  ['title' => 'Halaman RKBU',  'routePrefix' => 'rkbu_persediaans',]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RkbuPersediaan $rkbuPersediaan)
    { {
            // Pastikan session 'id_user' dan 'tahun_anggaran' tersedia
            if (!session()->has('id_user') || !session()->has('tahun_anggaran')) {
                return redirect()->back()->with('error', 'Data session tidak tersedia. Silakan login kembali.');
            }

            // Ambil data dari session
            $id_user = session('id_user');
            $nama_tahun_anggaran = session('tahun_anggaran');

            // Validasi input
            $validator  = Validator::make(
                $request->all(),
                [
                    'id_sub_kategori_rkbu'          => 'required',
                    'id_kode_rekening_belanja'      => 'nullable',
                    'barang'                        => 'nullable|string|max:255',
                    'nama_barang'                   => 'nullable|string|max:255',
                    'stok'                          => 'required|integer|min:0',
                    'rata_rata_pemakaian'           => 'required|numeric|min:0',
                    'kebutuhan_per_bulan'           => 'required|numeric|min:0',
                    'buffer'                        => 'required|numeric|min:0',
                    'pengadaan_sebelumnya'          => 'required|numeric|min:0',
                    'proyeksi_sisa_stok'            => 'required|numeric|min:0',
                    'kebutuhan_plus_buffer'         => 'required|numeric|min:0',
                    'kebutuhan_tahun_x1'            => 'required|numeric|min:0',
                    'rencana_pengadaan_tahun_x1'    => 'required|numeric|min:0',
                    'satuan_1'                      => 'required|string|max:255',
                    'id_status_validasi'            => 'nullable',
                    'spek'                          => 'nullable|string',
                    'harga_satuan'                  => 'required|numeric|min:0',
                    'ppn'                           => 'required|numeric|min:0|max:100',
                    'rating'                        => 'nullable',
                    'link_ekatalog'                 => 'nullable',
                    'upload_file_1'                 => 'nullable|mimes:pdf|max:2048',
                    'upload_file_2'                 => 'nullable|mimes:pdf|max:2048',
                    'upload_file_3'                 => 'nullable|mimes:pdf|max:2048',
                    'upload_file_4'                 => 'nullable|mimes:pdf|max:2048',
                ],
                [
                    // Custom error messages
                    'stok.required'                => 'Stok wajib diisi.',
                    'harga_satuan.required'        => 'Harga satuan wajib diisi dan harus berupa angka.',
                    'ppn.numeric'                  => 'PPN harus berupa angka.',
                    'ppn.min'                      => 'PPN tidak boleh kurang dari 0.',
                    'ppn.max'                      => 'PPN tidak boleh lebih dari 100.',
                    'upload_file_1.mimes'          => 'File yang diunggah harus berformat PDF.',
                    'upload_file_1.max'            => 'File PDF tidak boleh lebih dari 2MB.',
                    'rata_rata_pemakaian'           => 'Rata-rata Pemakaian wajib diisi',
                    'kebutuhan_per_bulan'           => 'Kebutuhan Per Bulan wajib diisi',
                    'buffer'                        => 'Buffer wajib diisi',
                    'pengadaan_sebelumnya'          => 'Pengadaan Sebelumnya wajib diisi',
                    'satuan_1'                      => 'Satuan wajib diisi',
                    'link_ekatalog'                 => 'Link Ekatalog belum diisi',
                ]
            );

            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessages = implode(' ', $errors->all());

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
            }

            // Prioritaskan 'nama_barang' (input manual), jika ada
            $barang = $request->input('nama_barang') ?? $request->input('barang');

            // Mengatur file upload hanya jika file ada
            $namaFile1 = $request->hasFile('upload_file_1') ? $request->file('upload_file_1')->store('public/uploads') : null;
            $namaFile2 = $request->hasFile('upload_file_2') ? $request->file('upload_file_2')->store('public/uploads') : null;
            $namaFile3 = $request->hasFile('upload_file_3') ? $request->file('upload_file_3')->store('public/uploads') : null;
            $namaFile4 = $request->hasFile('upload_file_4') ? $request->file('upload_file_4')->store('public/uploads') : null;

            // Menghitung total anggaran
            $vol1 = $request->input('vol_1', 0);
            $vol2 = $request->input('vol_2', 0);
            $harga_satuan = (int) str_replace('.', '', $request->input('harga_satuan'));
            $ppn = $request->input('ppn', 0);
            $rencana_kebutuhan = $request->input('rencana_pengadaan_tahun_x1');

            $jumlahVol = $rencana_kebutuhan;
            $totalAnggaran = ($jumlahVol * $harga_satuan) + ($ppn / 100 * ($jumlahVol * $harga_satuan));
            $sisa_vol_rkbu = $jumlahVol;
            $sisa_anggaran_rkbu = $totalAnggaran;


            // Isi data sesuai request
            $rkbuPersediaan->fill([
                'id_sub_kategori_rekening'      => $request->input('id_sub_kategori_rekening'),
                // 'id_kategori_rkbu'              => $request->input('id_kegiatan'),
                'id_sub_kategori_rkbu'          => $request->input('id_sub_kategori_rkbu'),
                'id_kode_rekening_belanja'      => $request->input('id_kode_rekening_belanja'),
                'id_user'                       => $id_user,
                'nama_barang'                   => $barang,
                'vol_1'                         => $vol1,
                'satuan_1'                      => $request->input('satuan_1'),
                'id_status_validasi'            => $request->input('id_status_validasi'),
                'id_status_validasi_rka'         => $request->input('id_status_validasi_rka', '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34'), // Berikan nilai default jika kosong
                'stok'                           => $request->input('stok'),
                'rata_rata_pemakaian'            => $request->input('rata_rata_pemakaian'),
                'kebutuhan_per_bulan'            => $request->input('kebutuhan_per_bulan'),
                'buffer'                         => $request->input('buffer'),
                'pengadaan_sebelumnya'           => $request->input('pengadaan_sebelumnya'),
                'proyeksi_sisa_stok'             => $request->input('proyeksi_sisa_stok'),
                'kebutuhan_plus_buffer'          => $request->input('kebutuhan_plus_buffer'),
                'kebutuhan_tahun_x1'             => $request->input('kebutuhan_tahun_x1'),
                'rencana_pengadaan_tahun_x1'     => $request->input('rencana_pengadaan_tahun_x1'),
                'vol_2'                         => $vol2,
                'satuan_2'                      => $request->input('satuan_2', 0),
                'jumlah_vol'                    => $jumlahVol,
                'spek'                          => $request->input('spek'),
                'harga_satuan'                  => $harga_satuan,
                'ppn'                           => $ppn,
                'total_anggaran'                => $totalAnggaran,
                'rating'                        => $request->input('rating'),
                'link_ekatalog'                 => $request->input('link_ekatalog'),
                'nama_tahun_anggaran'           => $nama_tahun_anggaran,
                'penempatan'                    => $request->input('penempatan', 0),
                'upload_file_1'                  => $namaFile1,
                'upload_file_2'                  => $namaFile2,
                'upload_file_3'                  => $namaFile3,
                'upload_file_4'                  => $namaFile4,
                'id_status_validasi_rka'         => $request->input('id_status_validasi_rka', '9cfb1f93-70fb-4b88-bff9-a3ae6e81ae34'), // Berikan nilai default jika kosong
                'nama_pegawai'                   => $request->input('nama_pegawai', 0),
                'tempat_lahir'                   => $request->input('tempat_lahir', 0),
                'tanggal_lahir'                   => $request->input('tanggal_lahir', 0),
                'pendidikan'                     => $request->input('pendidikan', 0),
                'jabatan'                        => $request->input('jabatan', 0),
                'status_kawin'                     => $request->input('status_kawin', 0),
                'nomor_kontrak'                     => $request->input('nomor_kontrak', 0),
                'tmt_pegawai'                     => $request->input('tmt_pegawai', 0),
                'bulan_tmt'                     => $request->input('bulan_tmt', 0),
                'tahun_tmt'                     => $request->input('tahun_tmt', 0),
                'gaji_pokok'                     => $request->input('gaji_pokok', 0),
                'remunerasi'                     => $request->input('remunerasi', 0),
                'koefisien_remunerasi'           => $request->input('koefisien_remunerasi', 0),
                'koefisien_gaji'                     => $request->input('koefisien_gaji', 0),
                'bpjs_kesehatan'                     => $request->input('bpjs_kesehatan', 0),
                'bpjs_tk'                     => $request->input('bpjs_tk', 0),
                'total_gaji_pokok'                     => $request->input('total_gaji_pokok', 0),
                'total_remunerasi'                     => $request->input('total_remunerasi', 0),
                'sisa_vol_rkbu'                     => $sisa_vol_rkbu,
                'sisa_anggaran_rkbu'                     => $sisa_anggaran_rkbu,

            ]);

            // dd($rkbuPersediaan);

            // Simpan data
            $rkbuPersediaan->update();

            // Redirect dengan pesan sukses
            return redirect()->route('rkbu_persediaans.index')->with('success', 'Data Berhasil Ditambahkan');
        }
    }

    public function downloadReport()
    {
        // Tentukan path file yang akan didownload
        $filePath = storage_path('app/public/download/format_upload_rkbu.xls');


        // Cek jika file ada
        if (File::exists($filePath)) {
            // Download file
            return response()->download($filePath);
        } else {
            // Jika file tidak ada
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rkbuPersediaan = RkbuPersediaan::findOrFail($id);
        $rkbuPersediaan->delete();

        return redirect()->route('rkbu_persediaans.index')
            ->with('success', 'RKBU Barang Jasa deleted successfully.');
    }
}
