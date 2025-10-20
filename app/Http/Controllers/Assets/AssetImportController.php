<?php

namespace App\Http\Controllers\Assets;

use App\Models\Asset\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AssetImportController extends Controller
{
    public function importForm()
    {
        return view('kategori_rkbu.import');
    }

    public function import(Request $request)
    {
        // Validasi file upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Ambil file yang di-upload
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Looping untuk memasukkan data dari file ke database
        foreach ($sheetData as $index => $row) {
            if ($index == 1) {
                // Lewati header (baris pertama)
                continue;
            }

            // Cek apakah kolom A tidak kosong (id_sub_kategori_rkbu)
            if (empty($row['A'])) {
                // Jika kosong, lewati baris ini
                continue;
            }

            Asset::create([
                'id_jenis_kategori_rkbu'    => $row['A'],
                'kode_asset'                => $row['B'],  // Asumsi kolom A adalah id_jenis_kategori_rkbu
                'nama_asset'                => $row['C'],  // Kolom B adalah id_obyek_belanja
                'satuan'                    => $row['D'],  // Kolom C adalah kode_kategori_rkbu
                'spek'                      => $row['E'],  // Kolom D adalah nama_kategori_rkbu
                'harga_asset'               => $row['F'],  // Kolom D adalah nama_kategori_rkbu
                'tahun_perolehan'           => $row['G'],  // Kolom D adalah nama_kategori_rkbu
                'kondisi_asset'             => $row['H'],
                'penempatan_asset'          => $row['I'],
                'status_asset'              => $row['J'],
                'id_barang'                 => $row['K'],
                'jumlah_asset'              => $row['L'],
                'total_anggaran_asset'      => $row['M'],
                'merk'                      => $row['N'],
                'qrcode_path'               => $row['O'],
                'serial_number'             => $row['P'],
                'no_register'               => $row['Q'],
                'type'                      => $row['R'],
                'tgl_bpkb'                  => $row['S'],
                'no_rangka'                 => $row['T'],
                'no_mesin'                  => $row['U'],
                'no_polisi'                 => $row['V'],
                'kapitalisasi'              => $row['W'],
                'link_detail'               => $row['X'],
                'foto'                      => $row['Y'],

            ]);
        }

        // dd($sheetData);

        return redirect()->back()->with('success', 'Data berhasil diimport!');
    }

    public function downloadReport()
    {
        // Tentukan path file yang akan didownload
        $filePath = storage_path('app/public/download/upload_assets.xls');


        // Cek jika file ada
        if (File::exists($filePath)) {
            // Download file
            return response()->download($filePath);
        } else {
            // Jika file tidak ada
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
    }
}
