<?php

namespace App\Http\Controllers\Import\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\MasterBackend\SettingRkbu\Rkbu;

class ImportRkbu extends Controller
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

            Rkbu::create([
                'id_sub_kategori_rkbu'           => $row['A'],  // Asumsi kolom A adalah id_jenis_kategori_rkbu
                'id_sub_kategori_rekening'       => $row['B'],  // Kolom B adalah id_obyek_belanja
                'id_kode_rekening_belanja'       => $row['C'],  // Kolom C adalah kode_kategori_rkbu
                'id_status_validasi'             => $row['D'],  // Kolom D adalah nama_kategori_rkbu
                'id_status_validasi_rka'         => $row['E'],  // Kolom D adalah nama_kategori_rkbu
                'id_user'                        => $row['F'],  // Kolom D adalah nama_kategori_rkbu
                'nama_barang'                    => $row['G'],
                'vol_1'                          => $row['H'],
                'satuan_1'                       => $row['I'],
                'vol_2'                          => $row['J'],
                'satuan_2'                       => $row['K'],
                'spek'                           => $row['L'],
                'jumlah_vol'                     => $row['M'],
                'harga_satuan'                   => $row['N'],
                'ppn'                            => $row['O'],
                'total_anggaran'                 => $row['P'],
                'rating'                         => $row['Q'],
                'nama_tahun_anggaran'            => $row['R'],
                'link_ekatalog'                  => $row['S'],
                'upload_file_1'                  => $row['T'],
                'upload_file_2'                  => $row['U'],
                'upload_file_3'                  => $row['V'],
                'upload_file_4'                  => $row['W'],
                'keterangan_status'              => $row['X'],
                'penempatan'                     => $row['Y'],
                'sisa_vol_rkbu'                  => $row['Z'],
                'sisa_anggaran_rkbu'             => $row['AA'],
                'stok'                           => $row['AB'],
                'rata_rata_pemakaian'            => $row['AC'],
                'kebutuhan_per_bulan'            => $row['AD'],
                'buffer'                         => $row['AE'],
                'pengadaan_sebelumnya'           => $row['AF'],
                'proyeksi_sisa_stok'             => $row['AG'],
                'kebutuhan_plus_buffer'          => $row['AH'],
                'kebutuhan_tahun_x1'             => $row['AI'],
                'rencana_pengadaan_tahun_x1'     => $row['AJ'],
                'nama_pegawai'                   => $row['AK'],
                'tempat_lahir'                   => $row['AL'],
                'tanggal_lahir'                  => $row['AM'],
                'pendidikan'                     => $row['AN'],
                'jabatan'                        => $row['AO'],
                'status_kawin'                   => $row['AP'],
                'nomor_kontrak'                  => $row['AQ'],
                'tmt_pegawai'                    => $row['AR'],
                'bulan_tmt'                      => $row['AS'],
                'tahun_tmt'                      => $row['AT'],
                'gaji_pokok'                     => $row['AU'],
                'remunerasi'                     => $row['AV'],
                'koefisien_remunerasi'           => $row['AW'],
                'koefisien_gaji'                 => $row['AX'],
                'bpjs_kesehatan'                 => $row['AY'],
                'bpjs_tk'                        => $row['AZ'],
                'bpjs_jht'                       => $row['BA'],
                'total_gaji_pokok'               => $row['BB'],
                'total_remunerasi'               => $row['BC'],
                'status_komponen'                => $row['BD'],
                'standar_kebutuhan'              => $row['BE'],
                'eksisting'                      => $row['BF'],
                'kondisi_baik'                   => $row['BG'],
                'kondisi_rusak_berat'            => $row['BH'],


            ]);
        }

        // dd($sheetData);

        return redirect()->back()->with('success', 'Data berhasil diimport!');
    }

    public function downloadReport()
    {
        $path = storage_path('app/public/uploads/format_upload_rkbu.xls');

        if (file_exists($path)) {
            return response()->download($path);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}
