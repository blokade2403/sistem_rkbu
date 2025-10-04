<?php

namespace App\Exports\UsulanBelanja;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;

class UsulanDetailExport implements FromCollection, WithHeadings, WithEvents
{
    protected $id_usulan_barang;

    // Constructor menerima parameter id_usulan_barang
    public function __construct($id_usulan_barang)
    {
        $this->id_usulan_barang = $id_usulan_barang;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data usulan barang berdasarkan id_usulan_barang
        $idUsulanBarang = $this->id_usulan_barang;

        if (!$idUsulanBarang) {
            return collect([]); // Kembalikan koleksi kosong jika id_usulan_barang tidak ditemukan
        }

        $usulanBarang = UsulanBarang::find($idUsulanBarang);

        if (!$usulanBarang) {
            return collect([]); // Kembalikan koleksi kosong jika data tidak ditemukan
        }

        return UsulanBarangDetail::where('usulan_barang_details.id_usulan_barang', $idUsulanBarang)
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'usulan_barang_details.id_usulan_barang')
            ->select(
                'usulan_barang_details.id_usulan_barang_detail',
                'usulan_barang_details.id_usulan_barang',
                'rkbus.nama_barang',
                'usulan_barang_details.vol_1_detail',
                'usulan_barang_details.satuan_1_detail',
                'usulan_barang_details.vol_2_detail',
                'usulan_barang_details.satuan_2_detail',
                'usulan_barang_details.satuan_2_detail',
                'usulan_barang_details.spek_detail',
                'usulan_barang_details.harga_barang',
                'usulan_barang_details.ppn',
                'usulan_barang_details.total_ppn',
                'usulan_barang_details.jumlah_usulan_barang',
                'usulan_barang_details.total_anggaran_usulan_barang',
                'usulan_barang_details.rata2_pemakaian',
                'usulan_barang_details.sisa_stok',
                'usulan_barang_details.stok_minimal',
                'usulan_barang_details.buffer_stok',
                'usulan_barang_details.jumlah_vol_rkbu',
                'usulan_barang_details.created_at',
                'usulan_barangs.no_usulan_barang',
                'usulan_barangs.nama_pengusul_barang',
                'usulan_barangs.status_usulan_barang',
                'usulan_barangs.status_permintaan_barang',
                'usulan_barangs.tahun_anggaran',
                'usulan_barangs.tgl_validasi_perencana',
                'usulan_barangs.tgl_validasi_kabag',
                'usulan_barangs.tgl_validasi_direktur',
            )
            ->get()
            ->map(function ($detail) {
                return [
                    $detail->id_usulan_barang,
                    $detail->id_usulan_barang_detail,
                    $detail->no_usulan_barang,
                    $detail->nama_pengusul_barang,
                    $detail->status_usulan_barang,
                    $detail->tahun_anggaran,
                    $detail->nama_barang,
                    $detail->vol_1_detail,
                    $detail->satuan_1_detail,
                    $detail->vol_2_detail,
                    $detail->satuan_2_detail,
                    $detail->satuan_2_detail,
                    $detail->spek_detail,
                    $detail->harga_barang,
                    $detail->ppn,
                    $detail->total_ppn,
                    $detail->jumlah_usulan_barang,
                    $detail->total_anggaran_usulan_barang,
                    $detail->rata2_pemakaian,
                    $detail->sisa_stok,
                    $detail->stok_minimal,
                    $detail->buffer_stok,
                    $detail->jumlah_vol_rkbu,
                    $detail->created_at,
                    $detail->tgl_validasi_perencana,
                    $detail->tgl_validasi_kabag,
                    $detail->tgl_validasi_direktur,
                ];
            });
    }

    public function headings(): array
    {
        return [
            [
                'Report Data Usulan Barang Detail', // Baris pertama untuk judul kustom
            ],
            [
                'id_usulan_barang',
                'id_usulan_barang',
                'id_usulan_barang_detail',
                'no_usulan_barang',
                'nama_pengusul_barang',
                'status_usulan_barang',
                'tahun_anggaran',
                'nama_barang',
                'vol_1_detail',
                'satuan_1_detail',
                'vol_2_detail',
                'satuan_2_detail',
                'satuan_2_detail',
                'spek_detail',
                'harga_barang',
                'ppn',
                'total_ppn',
                'jumlah_usulan_barang',
                'total_anggaran_usulan_barang',
                'rata2_pemakaian',
                'sisa_stok',
                'stok_minimal',
                'buffer_stok',
                'jumlah_vol_rkbu',
                'created_at',
                'tgl_validasi_perencana',
                'tgl_validasi_kabag',
                'tgl_validasi_direktur',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Menggabungkan sel A1 sampai E1 untuk judul
                $event->sheet->mergeCells('A1:AB1');

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
