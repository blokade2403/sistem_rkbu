<?php

namespace App\Exports;

use App\Models\Asset\Asset;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportAsset implements FromCollection, WithHeadings, WithEvents
{

    public function collection()
    {
        return Asset::select(
            'kode_asset',
            'nama_asset',
            'satuan',
            'spek',
            'harga_asset',
            'tahun_perolehan',
            'kondisi_asset',
            'pengguna_asset',
            'nip_pengguna',
            'jabatan_pengguna',
            'status_asset',
            'id_barang',
            'jumlah_asset',
            'total_anggaran_asset',
            'merk',
            'qrcode_path',
            'serial_number',
            'no_register',
            'type',
            'tgl_bpkb',
            'no_rangka',
            'no_mesin',
            'no_polisi',
            'kapitalisasi',
            'link_detail',
            'foto',
            'id_penempatan',
            'hibah',
            'sumber_anggaran',
            'status_reklas_arb',
            'kategori_asset_bergerak',
        )->get();
    }

    public function headings(): array
    {
        return [
            [
                'Report Data Inventory Asset', // Baris pertama untuk judul kustom
            ],

            [
                'kode_asset',
                'nama_asset',
                'satuan',
                'spek',
                'harga_asset',
                'tahun_perolehan',
                'kondisi_asset',
                'pengguna_asset',
                'nip_pengguna',
                'jabatan_pengguna',
                'status_asset',
                'id_barang',
                'jumlah_asset',
                'total_anggaran_asset',
                'merk',
                'qrcode_path',
                'serial_number',
                'no_register',
                'type',
                'tgl_bpkb',
                'no_rangka',
                'no_mesin',
                'no_polisi',
                'kapitalisasi',
                'link_detail',
                'foto',
                'id_penempatan',
                'hibah',
                'sumber_anggaran',
                'status_reklas_arb',
                'kategori_asset_bergerak',

            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Menggabungkan sel A1 sampai dengan Z1 untuk judul
                $event->sheet->mergeCells('A1:AE1');

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
