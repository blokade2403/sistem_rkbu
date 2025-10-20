<?php

namespace App\Exports;

use App\Models\Rkbu;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RkbuExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil session id_user dan tahun_anggaran
        $idUser = session('id_user');
        $tahunAnggaran = session('tahun_anggaran');

        // Query RKBU berdasarkan session id_user dan tahun_anggaran
        return Rkbu::with(['subKategoriRkbu', 'sub_kategori_rekenings', 'sub_kegiatans', 'status_validasi_rka', 'status_validasi', 'rekening_belanjas', 'user'])
            ->where('id_user', $idUser) // Filter berdasarkan id_user
            ->where('nama_tahun_anggaran', $tahunAnggaran) // Filter berdasarkan tahun anggaran
            ->get()->map(function ($rkbu) {
                return [
                    'id_sub_kategori_rekening' => $rkbu->sub_kategori_rekenings->nama_sub_kategori_rekening,
                    'nama_sub_kategori_rkbu' => $rkbu->subKategoriRkbu->nama_sub_kategori_rkbu, // Dari relasi subKategoriRkbu
                    'id_sub_kegiatan' => $rkbu->id_sub_kegiatan,
                    'nama_status_validasi' => $rkbu->status_validasi->nama_validasi, // Dari relasi statusValidasi
                    'nama_status_validasi_rka' => $rkbu->status_validasi_rka->nama_validasi_rka, // Dari relasi statusValidasiRka
                    'nama_rekening_belanja' => $rkbu->rekening_belanjas->nama_rekening_belanja, // Dari relasi rekeningBelanja
                    'id_user' => $rkbu->user->nama_lengkap,
                    'nama_barang' => $rkbu->nama_barang,
                    'vol_1' => $rkbu->vol_1,
                    'satuan_1' => $rkbu->satuan_1,
                    'vol_2' => $rkbu->vol_2,
                    'satuan_2' => $rkbu->satuan_2,
                    'spek' => $rkbu->spek,
                    'jumlah_vol' => $rkbu->jumlah_vol,
                    'harga_satuan' => $rkbu->harga_satuan,
                    'ppn' => $rkbu->ppn,
                    'total_anggaran' => $rkbu->total_anggaran,
                    'rating' => $rkbu->rating,
                    'nama_tahun_anggaran' => $rkbu->nama_tahun_anggaran,
                    'link_ekatalog' => $rkbu->link_ekatalog,
                    'upload_file_1' => $rkbu->upload_file_1,
                    'upload_file_2' => $rkbu->upload_file_2,
                    'upload_file_3' => $rkbu->upload_file_3,
                    'upload_file_4' => $rkbu->upload_file_4,
                    'keterangan_status' => $rkbu->keterangan_status,
                    'penempatan' => $rkbu->penempatan,
                    'stok' => $rkbu->stok,
                    'rata_rata_pemakaian' => $rkbu->rata_rata_pemakaian,
                    'kebutuhan_per_bulan' => $rkbu->kebutuhan_per_bulan,
                    'buffer' => $rkbu->buffer,
                    'pengadaan_sebelumnya' => $rkbu->pengadaan_sebelumnya,
                    'proyeksi_sisa_stok' => $rkbu->proyeksi_sisa_stok,
                    'kebutuhan_plus_buffer' => $rkbu->kebutuhan_plus_buffer,
                    'kebutuhan_tahun_x1' => $rkbu->kebutuhan_tahun_x1,
                    'rencana_pengadaan_tahun_x1' => $rkbu->rencana_pengadaan_tahun_x1,
                    'nama_pegawai' => $rkbu->nama_pegawai,
                    'tempat_lahir' => $rkbu->tempat_lahir,
                    'tanggal_lahir' => $rkbu->tanggal_lahir,
                    'pendidikan' => $rkbu->pendidikan,
                    'jabatan' => $rkbu->jabatan,
                    'status_kawin' => $rkbu->status_kawin,
                    'nomor_kontrak' => $rkbu->nomor_kontrak,
                    'tmt_pegawai' => $rkbu->tmt_pegawai,
                    'bulan_tmt' => $rkbu->bulan_tmt,
                    'gaji_pokok' => $rkbu->gaji_pokok,
                    'remunerasi' => $rkbu->remunerasi,
                    'koefisien_remunerasi' => $rkbu->koefisien_remunerasi,
                    'koefisien_gaji' => $rkbu->koefisien_gaji,
                    'bpjs_kesehatan' => $rkbu->bpjs_kesehatan,
                    'bpjs_tk' => $rkbu->bpjs_tk,
                    'bpjs_jht' => $rkbu->bpjs_jht,
                    'total_gaji_pokok' => $rkbu->total_gaji_pokok,
                    'total_remunerasi' => $rkbu->total_remunerasi,
                    'sisa_vol_rkbu' => $rkbu->sisa_vol_rkbu,
                    'sisa_anggaran_rkbu' => $rkbu->sisa_anggaran_rkbu,
                    'status_komponen' => $rkbu->status_komponen,
                    'standar_kebutuhan' => $rkbu->standar_kebutuhan,
                    'eksisting' => $rkbu->eksisting,
                    'kondisi_baik' => $rkbu->kondisi_baik,
                    'kondisi_rusak_berat' => $rkbu->kondisi_rusak_berat,
                ];
            });
    }


    public function headings(): array
    {
        return [
            [
                'Report Data RKBU', // Baris pertama untuk judul kustom
            ],

            [
                'id_sub_kategori_rekening',
                'id_sub_kategori_rkbu',
                'id_sub_kegiatan',
                'id_status_validasi',
                'id_status_validasi_rka',
                'id_kode_rekening_belanja',
                'id_user',
                'nama_barang',
                'vol_1',
                'satuan_1',
                'vol_2',
                'satuan_2',
                'spek',
                'jumlah_vol',
                'harga_satuan',
                'ppn',
                'total_anggaran',
                'rating',
                'nama_tahun_anggaran',
                'link_ekatalog',
                'upload_file_1',
                'upload_file_2',
                'upload_file_3',
                'upload_file_4',
                'keterangan_status',
                'penempatan',
                'stok',
                'rata_rata_pemakaian',
                'kebutuhan_per_bulan',
                'buffer',
                'pengadaan_sebelumnya',
                'proyeksi_sisa_stok',
                'kebutuhan_plus_buffer',
                'kebutuhan_tahun_x1',
                'rencana_pengadaan_tahun_x1',
                'nama_pegawai',
                'tempat_lahir',
                'tanggal_lahir',
                'pendidikan',
                'jabatan',
                'status_kawin',
                'nomor_kontrak',
                'tmt_pegawai',
                'bulan_tmt',
                'gaji_pokok',
                'remunerasi',
                'koefisien_remunerasi',
                'koefisien_gaji',
                'bpjs_kesehatan',
                'bpjs_tk',
                'bpjs_jht',
                'total_gaji_pokok',
                'total_remunerasi',
                'sisa_vol_rkbu',
                'sisa_anggaran_rkbu',
                'status_komponen',
                'standar_kebutuhan',
                'eksisting',
                'kondisi_baik',
                'kondisi_rusak_berat',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Menggabungkan sel A1 sampai dengan Z1 untuk judul
                $event->sheet->mergeCells('A1:Z1');

                // Menambahkan style untuk judul di A1
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            }
        ];
    }
}
