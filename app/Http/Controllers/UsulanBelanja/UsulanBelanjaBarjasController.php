<?php

namespace App\Http\Controllers\UsulanBelanja;

use Endroid\QrCode\QrCode;
use Illuminate\Support\Str;
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

class UsulanBelanjaBarjasController extends Controller
{
    public function index($id_sub_kategori_rkbu = null)
    {
        // dd(session()->all());
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = Session::get('tahun_anggaran');
        $inputIdUsulanBarang = request()->input('id_usulan_barang'); // Ambil input id_usulan_barang dari request
        $id_sub_kategori_rkbu = request()->input('id_sub_kategori_rkbu');

        // Ambil usulan barang yang pending
        $usulan_barangs = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->where('usulan_barangs.id_user', $id_user)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('status_usulan_barang', 'Pending')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('usulan_barangs.*')
            ->get();


        $id_usulan = UsulanBarang::join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            // Tambahkan leftJoin untuk memeriksa data usulan_barang_details
            ->leftJoin('usulan_barang_details', 'usulan_barang_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->where('usulan_barangs.id_user', $id_user)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('status_usulan_barang', 'Pending')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('usulan_barangs.*') // Ambil id_usulan_barang_detail juga
            //->select('usulan_barangs.*', 'usulan_barang_details.id_usulan_barang_detail') // Ambil id_usulan_barang_detail juga
            ->distinct()
            ->get();

        $sumber_Dana = ['9cdfced0-87ce-49dc-8268-25ed56420bf7', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3'];

        $sub_kategori_rkbus = SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->where('ksps.id_ksp', $id_ksp)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac')
            ->select('sub_kategori_rkbus.*') // Pilih kolom yang diinginkan dari sub_kategori_rkbus
            ->distinct() // Menghapus duplikasi berdasarkan sub_kategori_rkbus yang sama
            ->get();

        $get_barangs = [];
        foreach ($usulan_barangs as $yek) {
            $barang = Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_Dana)
                // ->where('sumber_danas.id_sumber_dana', '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3')
                ->where('rkbus.id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->where('ksps.id_ksp', $id_ksp)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_sub_kategori_rkbu', $yek->id_sub_kategori_rkbu) // Filter by sub_kategori_rkbu for this usulan
                ->select('rkbus.*')
                ->get();

            // Simpan data barang per usulan
            $get_barangs[$yek->id_usulan_barang] = $barang;
        }

        return view('usulan_belanja.index', compact('usulan_barangs', 'get_barangs', 'id_usulan', 'sub_kategori_rkbus'), ['title' => 'Halaman belanja', 'routePrefix' => 'usulan_barang_barjass']);
    }

    public function create()
    {
        //
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
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barang_details.id_sub_kategori_rkbu')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->where('usulan_barang_details.id_usulan_barang', $id_usulan_barang)
            ->select('usulan_barang_details.*', 'sub_kategori_rkbus.nama_sub_kategori_rkbu', 'users.nama_lengkap as nama_pengusul_barang', 'usulan_barangs.*')
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

        $catatan = DB::table('usulan_barangs')
            ->where('id_usulan_barang', $id_usulan_barang)
            ->value('keterangan_direktur');


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
            'catatan'           => $catatan,
            'routePrefix'       => 'usulan_barang_barjass'
        ];

        // dd($invo);

        // Return view dengan data yang disiapkan
        return view('usulan_belanja.keranjang', $data, ['title' => 'Halaman Belanja']);
    }

    public function addToCartMultiple(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);  // Ambil ID yang dipilih dari checkbox

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Tidak ada barang yang dipilih.');
        }

        foreach ($selectedIds as $id_rkbu) {
            // Ambil data produk berdasarkan id_rkbu
            $produk = $this->findRkbuById($id_rkbu);

            // Ambil invoice berdasarkan id_usulan_barang
            $invoice = $this->getInvoiceDetail($request->id_usulan_barang);

            if (!$invoice) {
                return redirect()->back()->with('error', 'Data invoice tidak ditemukan.');
            }

            // Ambil data detail dari keranjang
            $getdatadetail = $this->getCartProduk($invoice->id_usulan_barang, $produk->id_rkbu);

            // Variabel dari produk
            $harga_barang = $produk->harga_satuan;
            $satuan_1 = $produk->satuan_1;
            $satuan_2 = $produk->satuan_2;
            $spek = $produk->spek;
            $total_anggaran_belanja = $harga_barang;
            $id_sub_kategori_rkbu = $produk->id_sub_kategori_rkbu;

            if (!empty($invoice->no_usulan_barang)) {
                if (!empty($getdatadetail->id_usulan_barang_detail)) {
                    if ($produk->jumlah_vol > $getdatadetail->jumlah_usulan_barang) {
                        // Update keranjang
                        $data1 = [
                            'jumlah_usulan_barang' => $getdatadetail->jumlah_usulan_barang + 1,
                            'harga_barang' => $harga_barang,
                            'total_anggaran_usulan_barang' => $total_anggaran_belanja,
                            'total_ppn' => $total_anggaran_belanja * 0.1,
                            'id_sub_kategori_rkbu' => $id_sub_kategori_rkbu,
                        ];
                        $this->updateCart($getdatadetail->id_usulan_barang_detail, $data1);
                    }
                } else {
                    if ($produk->jumlah_vol >= 1) {
                        $data2 = [
                            'id_usulan_barang_detail'           => Str::uuid(),
                            'id_usulan_barang'                  => $invoice->id_usulan_barang,
                            'id_rkbu'                           => $produk->id_rkbu,
                            'jumlah_usulan_barang'              => 1,
                            'vol_1_detail'                      => 1,
                            'satuan_1_detail'                   => $satuan_1,
                            'vol_2_detail'                      => 1,
                            'satuan_2_detail'                   => $satuan_2,
                            'spek_detail'                       => $spek,
                            'harga_barang'                      => $harga_barang,
                            'total_anggaran_usulan_barang'      => $total_anggaran_belanja,
                            'id_sub_kategori_rkbu'              => $id_sub_kategori_rkbu,
                            'created_by'                        => auth()->user()->nama_lengkap,
                            'created_at'                        => now(),
                        ];
                        DB::table('usulan_barang_details')->insert($data2);
                    }
                }

                // Update stok dan anggaran setelah menambah barang
                $totalUsulanBarang = DB::table('usulan_barang_details')
                    ->where('id_rkbu', $id_rkbu)
                    ->sum('jumlah_usulan_barang');

                $sisa_vol_rkbu = $produk->jumlah_vol - $totalUsulanBarang;

                DB::table('rkbus')
                    ->where('id_rkbu', $id_rkbu)
                    ->update(['sisa_vol_rkbu' => $sisa_vol_rkbu]);

                $totalAnggaranUsulanBarang = DB::table('usulan_barang_details')
                    ->where('id_rkbu', $id_rkbu)
                    ->sum('total_anggaran_usulan_barang');

                $sisa_anggaran_rkbu = $produk->total_anggaran - $totalAnggaranUsulanBarang;

                DB::table('rkbus')
                    ->where('id_rkbu', $id_rkbu)
                    ->update(['sisa_anggaran_rkbu' => $sisa_anggaran_rkbu]);
            }
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    public function get_invoice_detail2($id_user = null)
    {
        return UsulanBarang::where('id_user', $id_user)
            ->where('status_usulan_barang', 'Pending')
            ->first();
    }

    public function get_usulan($id_usulan_barang)
    {
        return UsulanBarangDetail::where('id_usulan_barang', $id_usulan_barang)
            ->selectRaw('SUM(total_anggaran_usulan_barang) as total_anggaran_sum, SUM(total_ppn) as total_ppn_sum')
            ->get();
    }

    public function get_invoice_cetak2($id_usulan_barang)
    {
        return DB::table('usulan_barang_detail')
            ->join('usulan_barang', 'usulan_barang.id_usulan_barang', '=', 'usulan_barang_detail.id_usulan_barang')
            ->join('sub_kategori_rkbu', 'sub_kategori_rkbu.id_sub_kategori_rkbu', '=', 'usulan_barang_detail.id_sub_kategori_rkbu')
            ->join('rkbu', 'rkbu.id_rkbu', '=', 'usulan_barang_detail.id_rkbu')
            ->join('user', 'user.id_user', '=', 'usulan_barang.id_user')
            ->where('usulan_barang_detail.id_usulan_barang', $id_usulan_barang)
            ->get();
    }

    public function get_cart($invoiceno = null)
    {
        return DB::table('usulan_barang_detail')
            ->join('usulan_barang', 'usulan_barang.id_usulan_barang', '=', 'usulan_barang_detail.id_usulan_barang')
            ->join('rkbu', 'rkbu.id_rkbu', '=', 'usulan_barang_detail.id_rkbu')
            ->select(
                'jumlah_usulan_barang',
                'rkbu.harga_satuan',
                'usulan_barang_detail.ppn',
                'usulan_barang_detail.total_ppn',
                'harga_barang',
                'total_anggaran_usulan_barang',
                'usulan_barang_detail.spek_detail',
                'usulan_barang.no_usulan_barang',
                'id_usulan_barang_detail',
                'rkbu.id_rkbu',
                'rkbu.vol_usulan_barang',
                'rkbu.anggaran_usulan_barang',
                'usulan_barang_detail.id_rkbu',
                'rkbu.nama_barang',
                'rkbu.id_sub_kategori_rkbu',
                'rkbu.satuan_1'
            )
            ->where('usulan_barang_detail.id_usulan_barang', $invoiceno)
            ->get();
    }

    public function getRkbuData(Request $request)
    {
        $id_sub_kategori_rkbu = $request->input('id_sub_kategori_rkbu');

        // Ambil data RKBU berdasarkan id_sub_kategori_rkbu
        $rkbuData = Rkbu::where('id_sub_kategori_rkbu', $id_sub_kategori_rkbu)->get();

        // Kembalikan data dalam format JSON
        return response()->json(['data' => $rkbuData]);
    }

    public function getDataBySubKategori(Request $request)
    {
        $data = DB::table('sub_kategori_rkbus')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rkbus.id_sub_kategori_rekening', '=', 'sub_kategori_rekenings.id_sub_kategori_rekening')
            ->join('kategori_rekenings', 'sub_kategori_rekenings.id_kategori_rekening', '=', 'kategori_rekenings.id_kategori_rekening')

            ->select(
                'sub_kategori_rkbus.id_admin_pendukung_ppk',
                'sub_kategori_rkbus.id_kategori_rkbu',
                'jenis_kategori_rkbus.id_jenis_kategori_rkbu',
                'sub_kategori_rkbus.id_sub_kategori_rekening',
                'kategori_rekenings.nama_kategori_rekening',
                'kategori_rekenings.kode_kategori_rekening',
                'sub_kategori_rekenings.id_kategori_rekening',
                'sub_kategori_rekenings.kode_sub_kategori_rekening',
                'sub_kategori_rekenings.nama_sub_kategori_rekening',
            )
            ->where('sub_kategori_rkbus.id_sub_kategori_rkbu', $request->id_sub_kategori_rkbu)
            ->first();

        return response()->json($data);
    }

    public function findRkbuById($id_rkbu)
    {
        // Menggunakan Query Builder untuk mendapatkan data rkbus
        return DB::table('rkbus')->where('id_rkbu', $id_rkbu)->first();
    }

    public function addToCart(Request $request, $id_rkbu)
    {
        // Ambil data produk berdasarkan id_rkbu
        $produk = $this->findRkbuById($id_rkbu);

        // Ambil invoice berdasarkan id_usulan_barang
        $invoice = $this->getInvoiceDetail($request->id_usulan_barang);

        // Ambil data detail dari keranjang
        $getdatadetail = $this->getCartProduk($invoice->id_usulan_barang, $produk->id_rkbu);

        // Variabel-variabel yang diambil dari produk
        $harga_barang           = $produk->harga_satuan;
        $satuan_1               = $produk->satuan_1;
        $satuan_2               = $produk->satuan_2;
        $spek                   = $produk->spek;
        $ppn                    = $produk->ppn;
        $total_anggaran_belanja = $harga_barang;
        $id_sub_kategori_rkbu   = $produk->id_sub_kategori_rkbu;

        // Cek apakah nomor usulan barang ada
        if (!empty($invoice->no_usulan_barang)) {
            if (!empty($getdatadetail->id_usulan_barang_detail)) {
                // Jika jumlah volume produk lebih besar dari jumlah usulan barang
                if ($produk->jumlah_vol > $getdatadetail->jumlah_usulan_barang) {
                    // Update data keranjang
                    $data1 = [
                        'jumlah_usulan_barang'          => $getdatadetail->jumlah_usulan_barang + 1,
                        'harga_barang'                  => $harga_barang,
                        'total_anggaran_usulan_barang'  => $total_anggaran_belanja,
                        'total_ppn'                     => $total_anggaran_belanja * ($ppn / 100), // Contoh PPN 10%
                        'id_sub_kategori_rkbu'          => $id_sub_kategori_rkbu,
                    ];

                    // Update keranjang dengan data baru
                    $this->updateCart($getdatadetail->id_usulan_barang_detail, $data1);
                }
            } else {
                // Tambahkan produk baru ke keranjang jika tidak ada
                if ($produk->jumlah_vol >= 1) {
                    $data2 = [
                        'id_usulan_barang_detail'           => Str::uuid(),
                        'id_usulan_barang'                  => $invoice->id_usulan_barang,
                        'id_rkbu'                           => $produk->id_rkbu,
                        'jumlah_usulan_barang'              => 1,
                        'vol_1_detail'                      => 1,
                        'satuan_1_detail'                   => $satuan_1,
                        'vol_2_detail'                      => 1,
                        'satuan_2_detail'                   => $satuan_2,
                        'spek_detail'                       => $spek,
                        'harga_barang'                      => $harga_barang,
                        'total_anggaran_usulan_barang'      => $total_anggaran_belanja,
                        'id_sub_kategori_rkbu'              => $id_sub_kategori_rkbu,
                        'created_by'                        => auth()->user()->nama_lengkap,
                    ];

                    // Insert data ke dalam tabel detail usulan barang
                    DB::table('usulan_barang_details')->insert($data2);
                }
            }

            // Update sisa_vol_rkbu di tabel rkbus setelah barang berhasil ditambahkan
            $totalUsulanBarang = DB::table('usulan_barang_details')
                ->where('id_rkbu', $id_rkbu)
                ->sum('jumlah_usulan_barang');

            // Hitung sisa volume (sisa_vol_rkbu) = jumlah_vol - totalUsulanBarang
            $sisa_vol_rkbu = $produk->jumlah_vol - $totalUsulanBarang;

            // Update sisa_anggaran_rkbu di tabel rkbus setelah barang berhasil ditambahkan
            $totalAnggaranUsulanBarang = DB::table('usulan_barang_details')
                ->where('id_rkbu', $id_rkbu)
                ->sum('total_anggaran_usulan_barang');

            // Hitung sisa anggaran (sisa_anggaran_rkbu) = total_anggaran - totalAnggaranUsulanBarang
            $sisa_anggaran_rkbu = $produk->total_anggaran - $totalAnggaranUsulanBarang;

            // Validasi sebelum melakukan update
            if ($sisa_vol_rkbu >= 0 && $sisa_anggaran_rkbu >= 0) {
                // Update sisa_vol_rkbu dan sisa_anggaran_rkbu di tabel rkbus
                DB::table('rkbus')
                    ->where('id_rkbu', $id_rkbu)
                    ->update([
                        'sisa_vol_rkbu' => $sisa_vol_rkbu,
                        'sisa_anggaran_rkbu' => $sisa_anggaran_rkbu,
                    ]);

                return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang');
            } else {
                // Jika sisa volume atau anggaran kurang dari atau sama dengan 0, tampilkan alert
                return redirect()->back()->with('error', 'Usulan melebihi anggaran, silahkan melakukan pergeseran.');
            }
        }

        return redirect()->back()->with('error', 'Nomor usulan barang tidak valid');
    }

    public function getInvoiceDetail($id_usulan_barang)
    {
        return DB::table('usulan_barangs')->where('id_usulan_barang', $id_usulan_barang)->first();
    }

    public function getCartProduk($id_usulan_barang, $id_rkbu)
    {
        return DB::table('usulan_barang_details')
            ->where('id_usulan_barang', $id_usulan_barang)
            ->where('id_rkbu', $id_rkbu)
            ->first();
    }

    public function updateCart($id_usulan_barang_detail, $data)
    {
        DB::table('usulan_barang_details')
            ->where('id_usulan_barang_detail', $id_usulan_barang_detail)
            ->update($data);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make(
            $request->all(),
            [
                'id_sub_kategori_rkbu'              => 'required|uuid',
                'id_jenis_kategori_rkbu'            => 'required|uuid',
                'id_user'                           => 'required|uuid',
                'nama_pengusul_barang'              => 'required|string|max:255',
                'tahun_anggaran'                    => 'required|string|max:4',
                'status_usulan_barang'              => 'required|string',
            ],
            [
                'id_sub_kategori_rkbu.required'     => 'id_sub_kategori_rkbu Usulan Barang harus diisi.',
                'id_jenis_kategori_rkbu.required'   => 'id_jenis_kategori_rkbu ID Usulan Barang tidak valid.',
                'id_user.required'                  => 'Status SPJ harus diisi.',
                'nama_pengusul_barang.required'     => 'ID User harus diisi.',
                'tahun_anggaran.required'           => 'ID User tidak valid.',
                'status_usulan_barang.required'     => 'ID User tidak valid.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Format tanggal saat ini: YYYYMMDD
        $currentDate = date('Ymd');
        $prefix = 'INV' . $currentDate;

        // Cari no_usulan_barang terakhir yang dimulai dengan prefix ini
        $latestUsulan = UsulanBarang::where('no_usulan_barang', 'like', $prefix . '%')
            ->orderBy('no_usulan_barang', 'desc')
            ->first();

        if ($latestUsulan) {
            // Ambil 4 digit terakhir sebagai nomor urut
            $lastNumber = (int) substr($latestUsulan->no_usulan_barang, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; // Mulai dari 1 jika belum ada untuk hari ini
        }

        // Susun kode usulan baru
        $noUsulanBarang = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Simpan data ke database
        try {
            $usulanBarang  = new UsulanBarang();
            $usulanBarang->id_user = $request->id_user;
            $usulanBarang->id_sub_kategori_rkbu = $request->id_sub_kategori_rkbu;
            $usulanBarang->id_jenis_kategori_rkbu = $request->id_jenis_kategori_rkbu;
            $usulanBarang->nama_pengusul_barang = $request->nama_pengusul_barang;
            $usulanBarang->tahun_anggaran = $request->tahun_anggaran;
            $usulanBarang->no_usulan_barang = $noUsulanBarang;
            $usulanBarang->status_usulan_barang = $request->status_usulan_barang;
            $usulanBarang->status_permintaan_barang = 'Proses Usulan Barang';

            $usulanBarang->save();

            return redirect()->route('usulan_barang_barjass.index')->with('success', 'Data usulan barang berhasil ditambahkan!');
        } catch (\Illuminate\Database\QueryException $ex) {
            if ($ex->getCode() == 23000) {
                // Duplikat entri, bisa terjadi jika ada race condition
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: Nomor usulan sudah digunakan. Silakan coba lagi.');
            }
            // Kesalahan lain
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $ex->getMessage());
        }
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
            'routePrefix'       => 'usulan_barang_barjass',
        ];

        // dd($data);

        return view('usulan_belanja.invoice', $data, compact('sub_kategori_rkbus', 'usulan_barangs', 'id_usulan', 'invoice'), ['title' => 'Halaman Show', 'routePrefix' => 'usulan_barang_barjass']);
    }


    public function updateStatus(Request $request, $id)
    {
        // Validasi jika diperlukan
        $request->validate([
            // tambahkan aturan validasi jika diperlukan
        ]);

        // Ambil data usulan barang beserta relasi ke Ksp dan User
        $usulanBarang = UsulanBarang::with(['ksp', 'user', 'usulanBarangDetails.rkbu'])->findOrFail($id);
        // $usulanBarang = UsulanBarang::with('ksp', 'user')->findOrFail($id);

        // Ambil semua rkbus dari detail usulan
        if (!empty($usulanBarang->usulanBarangDetails)) {
            foreach ($usulanBarang->usulanBarangDetails as $detail) {
                if ($detail->rkbu && $detail->rkbu->sisa_anggaran_rkbu < 0) {
                    return redirect()->back()->with('error', 'Usulan tidak dapat dikirim karena sisa anggaran salah satu RKBU sudah habis.');
                }
            }
        }

        // Update status
        $usulanBarang->status_usulan_barang     = 'Selesai';
        $usulanBarang->status_permintaan_barang = 'Perlu Validasi PPTK';

        // Ambil id_user dari session
        $idUser = session('id_user');

        // Pastikan id_user tidak null dan cari user yang sesuai
        $namaPengusul = $usulanBarang->user->nama_lengkap;
        $nipKsp = $usulanBarang->user->nip_user; // NIP diambil dari users.nip_user

        // dd('nip :' . $nipKsp, 'nama valid : ' . $namaPengusul);

        // Data untuk QR code
        $dataQR = "Telah divalidasi oleh: " . $namaPengusul .
            "\nNIP: " . $nipKsp .
            "\nNo Usulan: " . $usulanBarang->no_usulan_barang .
            "\nTanggal: " . $usulanBarang->created_at->format('d-m-Y');

        // Generate QR code
        $qrcode = new QrCode($dataQR);

        // Mengatur ukuran
        $qrcode->setSize(300);

        // Menyimpan QR Code ke file (simpan sebagai gambar PNG)
        $writer = new PngWriter();
        $result = $writer->write($qrcode);

        // Simpan QR Code sebagai string (data gambar)
        $qrcodeImage = $result->getString();

        // Simpan QR code ke folder public/qrcodes
        $qrcodePath = 'qrcodes/qrcode_' . $usulanBarang->no_usulan_barang . '.png';
        Storage::disk('public')->put($qrcodePath, $qrcodeImage);

        // Simpan path QR code ke kolom qrcode_pengusul
        $usulanBarang->qrcode_pengusul = $qrcodePath;

        // dd($usulanBarang);
        // Simpan perubahan
        $usulanBarang->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('usulan_barang_barjass.show', ['id_usulan_barang' => $usulanBarang->id_usulan_barang])
            ->with('success', 'Status usulan barang berhasil diperbarui dan QR code telah dibuat.')
            ->with('qrcode', $qrcodePath);
    }

    public function edit($id)
    {
        $rkbuBarangJasa         = UsulanBarangDetail::with('usulan_barang')->findOrFail($id);
        $usulan_barangs             = UsulanBarang::all();
        $rkbu                       = Rkbu::where('id_rkbu', $rkbuBarangJasa->id_rkbu)->first();
        $uraian_satu                    = UraianSatu::all(); // Ambil uraian1
        $uraian_dua                    = UraianDua::all(); // Ambil uraian2

        // Ambil id_ksp, id_user, dan tahun_anggaran dari session
        $id_ksp = session('id_ksp');
        $id_user = session('id_user');
        $tahunAnggaran = session('tahun_anggaran'); // Lebih konsisten menggunakan session helper
        $status_validasi_rka = StatusValidasiRka::all(); // Ambil status validasi RKA

        // Kirim data ke view
        return view('usulan_belanja.edit', compact('rkbuBarangJasa', 'rkbu', 'uraian_satu', 'uraian_dua'), ['title' => '', 'routePrefix' => 'usulan_barang_barjass']);
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

            return redirect()->route('usulan_barang_barjass.keranjang', $usulanBarangDetail->id_usulan_barang)
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

        return redirect()->route('usulan_barang_barjass.index')->with('success', 'RKBU Barang Jasa dan detailnya berhasil dihapus.');
    }

    public function doDeleteDetailKeranjang($id_usulan_barang_detail)
    {
        // Ambil detail usulan barang berdasarkan id
        $usulanBarangDetail = UsulanBarangDetail::find($id_usulan_barang_detail);

        if (!$usulanBarangDetail) {
            return redirect()->back()->with('error', 'Detail usulan barang tidak ditemukan.');
        }

        // Ambil id_usulan_barang dan id_rkbu dari detail
        $id_usulan_barang = $usulanBarangDetail->id_usulan_barang;
        $id_rkbu = $usulanBarangDetail->id_rkbu;

        // Hapus detail usulan barang
        $usulanBarangDetail->delete();

        // Ambil data rkbu untuk menghitung volume dan anggaran sisa
        $rkbu = DB::table('rkbus')->where('id_rkbu', $id_rkbu)->first();

        // Periksa apakah rkbu ditemukan
        if (!$rkbu) {
            return redirect()->back()->with('error', 'RKBU tidak ditemukan.');
        }

        // Update sisa_vol_rkbu di tabel rkbus
        $totalUsulanBarang = DB::table('usulan_barang_details')
            ->where('id_rkbu', $id_rkbu)
            ->sum('jumlah_usulan_barang');

        // Hitung sisa volume (sisa_vol_rkbu) = jumlah_vol - totalUsulanBarang
        $sisa_vol_rkbu = $rkbu->jumlah_vol - $totalUsulanBarang;

        // Update sisa_vol_rkbu di tabel rkbus
        DB::table('rkbus')
            ->where('id_rkbu', $id_rkbu)
            ->update(['sisa_vol_rkbu' => $sisa_vol_rkbu]);

        // Update sisa_anggaran_rkbu di tabel rkbus
        $totalAnggaranUsulanBarang = DB::table('usulan_barang_details')
            ->where('id_rkbu', $id_rkbu)
            ->sum('total_anggaran_usulan_barang');

        // Hitung sisa anggaran (sisa_anggaran_rkbu) = total_anggaran - totalAnggaranUsulanBarang
        $sisa_anggaran_rkbu = $rkbu->total_anggaran - $totalAnggaranUsulanBarang;

        // Update sisa_anggaran_rkbu di tabel rkbus
        DB::table('rkbus')
            ->where('id_rkbu', $id_rkbu)
            ->update(['sisa_anggaran_rkbu' => $sisa_anggaran_rkbu]);

        // Redirect ke halaman keranjang usulan barang dengan pesan sukses
        return redirect()->route('usulan_barang_barjass.index', $id_usulan_barang)->with('success', 'Detail usulan barang berhasil dihapus.');
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
        return redirect()->route('usulan_barang_barjass.index')->with('success', 'Data usulan barang berhasil dihapus.');
    }
}
