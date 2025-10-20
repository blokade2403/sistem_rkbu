<?php

namespace App\Exports;

use App\Models\SubKategoriRkbu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubKategoriRkbuExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua data sub kategori rkbu beserta kolom tambahan
        return SubKategoriRkbu::join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('admin_pendukungs', 'sub_kategori_rkbus.id_admin_pendukung_ppk', '=', 'admin_pendukungs.id_admin_pendukung_ppk')
            ->join('sub_kategori_rekenings', 'sub_kategori_rkbus.id_sub_kategori_rekening', '=', 'sub_kategori_rekenings.id_sub_kategori_rekening')
            ->select(
                'sub_kategori_rkbus.id_sub_kategori_rkbu',
                'sub_kategori_rkbus.id_kategori_rkbu',
                'kategori_rkbus.kode_kategori_rkbu',      // Kolom dari tabel kategori_rkbus
                'kategori_rkbus.nama_kategori_rkbu',      // Kolom dari tabel kategori_rkbus
                'sub_kategori_rkbus.id_admin_pendukung_ppk',
                'admin_pendukungs.nama_pendukung_ppk', // Kolom dari tabel admin_pendukung_ppks
                'sub_kategori_rkbus.id_sub_kategori_rekening',
                'sub_kategori_rekenings.kode_sub_kategori_rekening', // Kolom dari tabel sub_kategori_rekenings
                'sub_kategori_rekenings.nama_sub_kategori_rekening', // Kolom dari tabel sub_kategori_rekenings
                'sub_kategori_rkbus.kode_sub_kategori_rkbu',
                'sub_kategori_rkbus.nama_sub_kategori_rkbu',
                'sub_kategori_rkbus.status'
            )->get();
    }

    /**
     * Define the headers for the columns.
     */
    public function headings(): array
    {
        return [
            'ID Sub Kategori RKBU',
            'ID Kategori RKBU',
            'Kode Kategori RKBU',
            'Nama Kategori RKBU',
            'ID Admin Pendukung PPK',
            'Nama Admin Pendukung PPK',
            'ID Sub Kategori Rekening',
            'Kode Sub Kategori Rekening',
            'Nama Sub Kategori Rekening',
            'Kode Sub Kategori RKBU',
            'Nama Sub Kategori RKBU',
            'Status',
        ];
    }
}
