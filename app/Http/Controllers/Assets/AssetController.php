<?php

namespace App\Http\Controllers\Assets;

use Endroid\QrCode\QrCode;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetHistory;
use App\Exports\ExportAsset;
use Illuminate\Http\Request;
use App\Models\Asset\Penempatan;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Maatwebsite\Excel\Facades\Excel;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Assets\AssetsDataTable;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class AssetController extends Controller
{

    public function index(AssetsDataTable $dataTable, Request $request)
    {
        $tahunOptions = Asset::select('tahun_perolehan')
            ->distinct()
            ->orderBy('tahun_perolehan', 'desc')
            ->pluck('tahun_perolehan');

        $kondisi = Asset::select('kondisi_asset')
            ->distinct()
            ->orderBy('kondisi_asset', 'desc')
            ->pluck('kondisi_asset');

        $penempatan = Asset::join('penempatans', 'assets.id_penempatan', '=', 'penempatans.id_penempatan')
            ->select('penempatans.id_penempatan', 'penempatans.lokasi_barang')
            ->distinct()
            ->orderBy('penempatans.lokasi_barang', 'asc')
            ->get();


        return $dataTable->render('asset.index', [
            'tahunOptions'      => $tahunOptions,
            'kondisi'           => $kondisi,
            'penempatan'        => $penempatan,
            'title'             => 'Halaman Asset',
            'routePrefix'       => 'assets'
        ]);
    }


    public function create()
    {
        $satuan = UraianSatu::all();
        $jenis  = JenisKategoriRkbu::all();
        $penempatan  = Penempatan::all();
        return view('asset.create', compact('satuan', 'jenis', 'penempatan'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'    =>  'nullable',
                'kode_asset'                =>  'nullable',
                'nama_asset'                =>  'nullable',
                'satuan'                    =>  'nullable',
                'spek'                      =>  'nullable',
                'harga_asset'               =>  'nullable',
                'tahun_perolehan'           =>  'nullable',
                'tgl_asset'                 =>  'nullable',
                'penyedia'                  =>  'nullable',
                'kondisi_asset'             =>  'nullable',
                'pengguna_asset'            =>  'nullable',
                'nip_pengguna'              =>  'nullable',
                'jabatan_pengguna'          =>  'nullable',
                'status_asset'              =>  'nullable',
                'id_barang'                 =>  'nullable',
                'jumlah_asset'              =>  'nullable',
                'total_anggaran_asset'      =>  'nullable',
                'merk'                      =>  'nullable',
                'qrcode_path'               =>  'nullable',
                'serial_number'             =>  'nullable',
                'no_register'               =>  'nullable',
                'type'                      =>  'nullable',
                'tgl_bpkb'                  =>  'nullable',
                'no_rangka'                 =>  'nullable',
                'no_mesin'                  =>  'nullable',
                'no_polisi'                 =>  'nullable',
                'kapitalisasi'              =>  'nullable',
                'link_detail'               =>  'nullable',
                'foto'                      =>  'nullable|mimes:jpg,jpeg,png|max:3072',
                'id_penempatan'             =>  'nullable',
                'hibah'                     =>  'nullable',
                'sumber_anggaran'           =>  'nullable',
                'kategori_asset_bergerak'   =>  'nullable',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            // dd($errors);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $foto1 = $request->hasFile('foto') ? $request->file('foto')->store('public/foto_asset') : null;

        $asset = new Asset([
            'id_jenis_kategori_rkbu'        => $request->id_jenis_kategori_rkbu,
            'kode_asset'                    => $request->kode_asset,
            'nama_asset'                    => $request->nama_asset,
            'satuan'                        => $request->satuan,
            'spek'                          => $request->spek,
            'harga_asset'                   => $request->harga_asset,
            'tahun_perolehan'               => $request->tahun_perolehan,
            'tgl_asset'                     => $request->tgl_asset,
            'penyedia'                      => $request->penyedia,
            'kondisi_asset'                 => $request->kondisi_asset,
            'pengguna_asset'                => $request->pengguna_asset,
            'nip_pengguna'                  => $request->nip_pengguna,
            'jabatan_pengguna'              => $request->jabatan_pengguna,
            'status_asset'                  => $request->status_asset,
            'id_barang'                     => $request->id_barang,
            'jumlah_asset'                  => $request->jumlah_asset,
            'total_anggaran_asset'          => $request->total_anggaran_asset,
            'merk'                          => $request->merk,
            'qrcode_path'                   => $request->qrcode_path,
            'serial_number'                 => $request->serial_number,
            'no_register'                   => $request->no_register,
            'type'                          => $request->type,
            'tgl_bpkb'                      => $request->tgl_bpkb,
            'no_rangka'                     => $request->no_rangka,
            'no_mesin'                      => $request->no_mesin,
            'no_polisi'                     => $request->no_polisi,
            'kapitalisasi'                  => $request->kapitalisasi,
            'link_detail'                   => $request->link_detail,
            'foto'                          => $foto1,
            'id_penempatan'                 => $request->id_penempatan,
            'hibah'                         => $request->hibah,
            'sumber_anggaran'               => $request->sumber_anggaran,
            'kategori_asset_bergerak'       => $request->kategori_asset_bergerak,

        ]);

        // dd($asset);
        $asset->save();
        return redirect()->route('barang_assets.index')->with('success', 'Program berhasil dibuat.');
    }

    public function generateQrCode($id_asset)
    {
        $asset = Asset::find($id_asset);

        if (!$asset) {
            return redirect()->back()->with('error', 'Asset tidak ditemukan.');
        }

        // URL detail untuk QR Code
        $link_detail = route('asset.scan', $id_asset);

        // Buat QR Code
        $qrCode = QrCode::create($link_detail)
            ->setSize(500)
            ->setMargin(10)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);

        // Gunakan GD Library untuk menulis QR Code
        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrImage = imagecreatefromstring($qrResult->getString());

        // Dapatkan ukuran QR Code
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);

        // Buat gambar dengan latar putih
        $finalWidth = $qrWidth + 40;
        $finalHeight = $qrHeight + 120;
        $finalImage = imagecreatetruecolor($finalWidth, $finalHeight);
        $white = imagecolorallocate($finalImage, 255, 255, 255);
        $black = imagecolorallocate($finalImage, 0, 0, 0);
        imagefilledrectangle($finalImage, 0, 0, $finalWidth, $finalHeight, $white);

        // Path font
        $fontPath = public_path('fonts/Roboto-VariableFont_wdth,wght.ttf');
        if (!file_exists($fontPath)) {
            return redirect()->back()->with('error', 'Font tidak ditemukan.');
        }

        // Tambahkan teks "INVENTARIS RSUD CILINCING" di atas QR code (DICENTERKAN)
        $text = "BARANG MODAL RSUD CILINCING";
        $fontSize = 12; // Ukuran font dikurangi agar lebih pas
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = abs($textBox[2] - $textBox[0]); // Lebar teks
        $textX = ($finalWidth - $textWidth) / 2; // Posisi tengah
        imagettftext($finalImage, $fontSize, 0, $textX, 55, $black, $fontPath, $text);

        // Tempelkan QR Code di tengah gambar
        imagecopyresampled($finalImage, $qrImage, 20, 80, 0, 0, $qrWidth, $qrHeight, $qrWidth, $qrHeight);

        // Tambahkan teks kode aset di bawah QR code
        $kodeAsset = strtoupper($asset->kode_asset . '-' . $asset->no_register);
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $kodeAsset);
        $textWidth = abs($textBox[2] - $textBox[0]); // Lebar teks
        $textX = ($finalWidth - $textWidth) / 2; // Posisi tengah
        imagettftext($finalImage, $fontSize, 0, $textX, $finalHeight - 10, $black, $fontPath, $kodeAsset);

        // Tambahkan Logo DKI Jakarta di kiri atas
        $logoDKIPath = public_path('logo_jaya.png');
        if (file_exists($logoDKIPath)) {
            $logoDKI = imagecreatefrompng($logoDKIPath);
            $logoWidth = imagesx($logoDKI);
            $logoHeight = imagesy($logoDKI);
            $newLogoWidth = 50;
            $newLogoHeight = ($logoHeight / $logoWidth) * $newLogoWidth;
            imagecopyresampled($finalImage, $logoDKI, 10, 5, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);
            imagedestroy($logoDKI);
        }

        // Tambahkan Logo BPAD di kanan atas
        $logoBPADPath = public_path('logo.png');
        if (file_exists($logoBPADPath)) {
            $logoBPAD = imagecreatefrompng($logoBPADPath);
            $logoWidth = imagesx($logoBPAD);
            $logoHeight = imagesy($logoBPAD);
            $newLogoWidth = 50;
            $newLogoHeight = ($logoHeight / $logoWidth) * $newLogoWidth;
            imagecopyresampled($finalImage, $logoBPAD, $finalWidth - 60, 5, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);
            imagedestroy($logoBPAD);
        }

        // Pastikan folder penyimpanan ada
        $qrCodeDir = storage_path('app/public/qrcode_assets/');
        if (!file_exists($qrCodeDir)) {
            mkdir($qrCodeDir, 0755, true);
        }

        // Simpan hasil akhir QR Code dengan logo dan teks
        $finalFileName = 'qrcode_' . $asset->id_asset . '_' . $asset->kode_asset . '_' . $asset->no_register . '.png';
        $finalPath = $qrCodeDir . $finalFileName;
        imagepng($finalImage, $finalPath, 5);

        // Bersihkan memori
        imagedestroy($qrImage);
        imagedestroy($finalImage);

        // Simpan path ke database
        $asset->update([
            'qrcode_path' => 'qrcode_assets/' . $finalFileName,
            'link_detail' => $link_detail,
        ]);

        return redirect()->back()->with('success', 'QR Code berhasil digenerate.');
    }

    public function show($id_asset)
    {
        $asset = Asset::find($id_asset);

        if (!$asset) {
            return redirect()->back()->with('error', 'Asset tidak ditemukan.');
        }

        return view('asset.detail', compact('asset'));
    }

    public function edit(string $id)
    {
        $satuan = UraianSatu::all();
        $jenis  = JenisKategoriRkbu::all();
        $penempatan  = Penempatan::all();
        $asset = Asset::where('id_asset', $id)->firstOrFail();

        return view('asset.edit', compact('satuan', 'jenis', 'asset', 'penempatan'), ['title' => 'Halaman Asset', 'title2' => 'Asset', 'routePrefix' => 'assets']);
    }

    public function reklas_update(Request $request, $id)
    {
        $asset = Asset::where('id_asset', $id)->firstOrFail();
        abort_unless($asset->kondisi_asset === 'Rusak Berat', 422, 'Usulan penghapusan hanya dapat diajukan untuk aset rusak berat.');
        if ($asset->status_reklas_arb !== 'Reklas') {
            $before = $asset->getAttributes();
            $asset->update([
                'status_reklas_arb' => 'Reklas',
            ]);
            AssetHistory::query()->create([
                'asset_id' => $asset->id_asset,
                'asset_unit_id' => $asset->unit_id,
                'asset_name' => $asset->nama_asset,
                'asset_register' => $asset->no_register,
                'action' => 'deletion_proposed',
                'description' => 'Usulan penghapusan diajukan dari modul aset lama.',
                'before_data' => $before,
                'after_data' => $asset->getAttributes(),
                'user_id' => auth()->id(),
            ]);
        }
        return redirect()->route('asset-module.index')->with('success', 'Usulan penghapusan aset dicatat. Riwayat aset tetap tersimpan.');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::where('id_asset', $id)->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'id_jenis_kategori_rkbu'    => 'nullable',
                'kode_asset'                => 'nullable',
                'nama_asset'                => 'nullable',
                'satuan'                    => 'nullable',
                'spek'                      => 'nullable',
                'harga_asset'               => 'nullable',
                'tahun_perolehan'           => 'nullable',
                'tgl_asset'                 =>  'nullable',
                'penyedia'                  =>  'nullable',
                'kondisi_asset'             => 'nullable',
                'pengguna_asset'            =>  'nullable',
                'nip_pengguna'              =>  'nullable',
                'jabatan_pengguna'          =>  'nullable',
                'status_asset'              => 'nullable',
                'id_barang'                 => 'nullable',
                'jumlah_asset'              => 'nullable',
                'total_anggaran_asset'      => 'nullable',
                'merk'                      => 'nullable',
                'qrcode_path'               => 'nullable',
                'serial_number'             => 'nullable',
                'no_register'               => 'nullable',
                'type'                      => 'nullable',
                'tgl_bpkb'                  => 'nullable',
                'no_rangka'                 => 'nullable',
                'no_mesin'                  => 'nullable',
                'no_polisi'                 => 'nullable',
                'kapitalisasi'              => 'nullable',
                'link_detail'               => 'nullable',
                'foto'                      => 'nullable|mimes:jpg,jpeg,png|max:3072',
                'upload_sptjm'              => 'nullable|mimes:pdf|max:3072',
                'id_penempatan'             => 'nullable',
                'hibah'                     => 'nullable',
                'sumber_anggaran'           => 'nullable',
                'status_reklas_arb'         => 'nullable',
                'kategori_asset_bergerak'   => 'nullable',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // Cek apakah ada file foto baru yang diupload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public/foto_asset');
        } else {
            $fotoPath = $asset->foto; // Gunakan foto lama jika tidak ada yang baru
        }

        // Simpan upload_sptjm jika ada
        if ($request->hasFile('upload_sptjm')) {
            $sptjmPath = $request->file('upload_sptjm')->store('public/upload_sptjms');
        } else {
            $sptjmPath = $asset->upload_sptjm ?? null;
        }

        // Cek apakah ada qrcode_path baru, jika tidak pakai yang lama
        $qrcodePath = $request->input('qrcode_path', $asset->qrcode_path);

        $asset->fill([
            'id_jenis_kategori_rkbu'        => $request->input('id_jenis_kategori_rkbu'),
            'kode_asset'                    => $request->input('kode_asset'),
            'nama_asset'                    => $request->input('nama_asset'),
            'satuan'                        => $request->input('satuan'),
            'spek'                          => $request->input('spek'),
            'harga_asset'                   => $request->input('harga_asset'),
            'tahun_perolehan'               => $request->input('tahun_perolehan'),
            'tgl_asset'                     => $request->input('tgl_asset'),
            'penyedia'                      => $request->input('penyedia'),
            'kondisi_asset'                 => $request->input('kondisi_asset'),
            'pengguna_asset'                => $request->input('pengguna_asset'),
            'nip_pengguna'                  => $request->input('nip_pengguna'),
            'jabatan_pengguna'              => $request->input('jabatan_pengguna'),
            'status_asset'                  => $request->input('status_asset'),
            'id_barang'                     => $request->input('id_barang'),
            'jumlah_asset'                  => $request->input('jumlah_asset'),
            'total_anggaran_asset'          => $request->input('total_anggaran_asset'),
            'merk'                          => $request->input('merk'),
            'qrcode_path'                   => $qrcodePath,
            'serial_number'                 => $request->input('serial_number'),
            'no_register'                   => $request->input('no_register'),
            'type'                          => $request->input('type'),
            'tgl_bpkb'                      => $request->input('tgl_bpkb'),
            'no_rangka'                     => $request->input('no_rangka'),
            'no_mesin'                      => $request->input('no_mesin'),
            'no_polisi'                     => $request->input('no_polisi'),
            'kapitalisasi'                  => $request->input('kapitalisasi'),
            'link_detail'                   => $request->input('link_detail'),
            'foto'                          => $fotoPath,
            'upload_sptjm'                  => $sptjmPath,
            'id_penempatan'                 => $request->input('id_penempatan'),
            'hibah'                         => $request->input('hibah'),
            'sumber_anggaran'               => $request->input('sumber_anggaran'),
            'status_reklas_arb'             => $request->input('status_reklas_arb'),
            'kategori_asset_bergerak'       => $request->input('kategori_asset_bergerak'),
        ]);

        // dd($asset);

        $asset->save();

        return redirect()->route('barang_assets.index')->with('success', 'Data Asset berhasil diperbarui.');
    }

    public function export()
    {
        return Excel::download(new ExportAsset, 'barang_assets.xlsx');
    }

    public function cetakQRCode(Request $request)
    {
        $tahun = $request->input('tahun_perolehan'); // Ambil input tahun dari form
        $id_penempatan = $request->input('id_penempatan');

        $asset = Asset::where(function ($query) {
            $query->where('status_reklas_arb', '!=', 'Reklas')
                ->orWhereNull('status_reklas_arb');
        })
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun_perolehan', $tahun);
            })
            ->when($id_penempatan, function ($query) use ($id_penempatan) {
                $query->where('id_penempatan', $id_penempatan);
            })
            ->get();

        $header = JudulHeader::first();

        $daftarTahun = Asset::select('tahun_perolehan')->distinct()->pluck('tahun_perolehan');
        $daftarPenempatan = Penempatan::select('id_penempatan', 'lokasi_barang')->distinct()->get();
        return view('asset.print_barcode', compact('asset', 'header', 'daftarTahun', 'daftarPenempatan'));
    }

    public function destroy(Asset $asset)
    {
        AssetHistory::query()->create([
            'asset_id' => $asset->id_asset,
            'asset_unit_id' => $asset->unit_id,
            'asset_name' => $asset->nama_asset,
            'asset_register' => $asset->no_register,
            'action' => 'deleted',
            'description' => 'Record aset dihapus permanen; salinan data aset tersimpan dalam riwayat.',
            'before_data' => $asset->getAttributes(),
            'after_data' => null,
            'user_id' => auth()->id(),
        ]);
        // Hapus foto jika ada
        if ($asset->foto && Storage::exists($asset->foto)) {
            Storage::delete($asset->foto);
        }

        // Hapus QR Code jika ada
        if ($asset->qrcode_path && Storage::exists('public/' . $asset->qrcode_path)) {
            Storage::delete('public/' . $asset->qrcode_path);
        }

        // Hapus data dari database
        $asset->delete();

        return redirect()->route('barang_assets.index')
            ->with('success', 'Asset berhasil dihapus beserta file terkait.');
    }

    public function downloadSptjm($id_asset)
    {
        $data = Asset::findOrFail($id_asset);
        // dd($data->upload_sptjm);
        if (!$data->upload_sptjm) {
            return back()->with('error', 'File SPTJM belum tersedia.');
        }

        // Path file di storage/app/public/upload_sptjms/
        $filePath = storage_path('app/' . $data->upload_sptjm);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File SPTJM tidak ditemukan di server.');
        }

        return response()->download($filePath);
    }
}
