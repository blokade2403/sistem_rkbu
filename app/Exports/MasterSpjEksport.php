<?php

namespace App\Exports;

use App\Models\MasterSpj;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterSpjEksport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil session id_user dan tahun_anggaran
        $idUser = session('id_user');
        $tahunAnggaran = session('tahun_anggaran');

        // Query master_spj berdasarkan session id_user dan tahun_anggaran
        $data = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->get();

        // dd($data);
        // Menampilkan semua id_master_spj
        $result = $data->map(function ($master_spj) {
            return [
                'id_master_spj'                 => $master_spj->id_master_spj,
                'no_usulan_barang'              => $master_spj->usulanBarang->no_usulan_barang ?? 'N/A',
                'nama_pengusul_barang'          => $master_spj->usulanBarang->nama_pengusul_barang ?? 'N/A',
                'tgl_validasi_perencana'        => $master_spj->usulanBarang->tgl_validasi_perencana ?? 'N/A',
                'tgl_validasi_kabag'            => $master_spj->usulanBarang->tgl_validasi_kabag ?? 'N/A',
                'tgl_validasi_direktur'         => $master_spj->usulanBarang->tgl_validasi_direktur ?? 'N/A',
                'tanggal_validasi_pengadaan'    => $master_spj->usulanBarang->tanggal_validasi_pengadaan ?? 'N/A',
                'status_hutang'                 => $master_spj->status_hutang ?? 'N/A',
                'nama_perusahaan'               => $master_spj->perusahaan->nama_perusahaan ?? 'N/A',
                'idpaket'                       => $master_spj->idpaket ?? 'N/A',
                'tgl_bast'                      => $master_spj->tgl_bast ?? 'N/A',
                'tgl_proses_faktur'             => $master_spj->tgl_proses_faktur ?? 'N/A',
                'no_ba_hp'                      => $master_spj->no_ba_hp ?? 'N/A',
                'no_ba_bp'                      => $master_spj->no_ba_bp ?? 'N/A',
                'tanggal_faktur'                => $master_spj->tanggal_faktur ?? 'N/A',
                'nomor_spk'                     => $master_spj->nomor_spk ?? 'N/A',
                'no_ba'                         => $master_spj->no_ba ?? 'N/A',
                'no_surat_pesanan'              => $master_spj->no_surat_pesanan ?? 'N/A',
                'no_dpa'                        => $master_spj->no_dpa ?? 'N/A',
                'tgl_surat_pesanan'             => $master_spj->tgl_surat_pesanan ?? 'N/A',
                'jangka_waktu_pekerjaan'        => $master_spj->jangka_waktu_pekerjaan ?? 'N/A',
                'tgl_proses_pemesanan'          => $master_spj->tgl_proses_pemesanan ?? 'N/A',
                'tgl_barang_datang'             => $master_spj->tgl_barang_datang ?? 'N/A',
                'nama_pendukung_ppk'            => $master_spj->adminPendukung->nama_pendukung_ppk ?? 'N/A',
                'keterangan_barang_datang'      => $master_spj->keterangan_barang_datang ?? 'N/A',
                'tanggal_penyerahan_spj'        => $master_spj->tanggal_penyerahan_spj ?? 'N/A',
                'bulan_penyerahan_spj'          => $master_spj->bulan_penyerahan_spj ?? 'N/A',
                'tanggal_revisi_spj'            => $master_spj->tanggal_revisi_spj ?? 'N/A',
                'harga_dasar'                   => $master_spj->harga_dasar ?? 'N/A',
                'ppn'                           => $master_spj->ppn ?? 'N/A',
                'pph21'                         => $master_spj->pph21 ?? 'N/A',
                'pph22'                         => $master_spj->pph22 ?? 'N/A',
                'pph23'                         => $master_spj->pph23 ?? 'N/A',
                'pp05'                          => $master_spj->pp05 ?? 'N/A',
                'jumlah_pajak'                  => $master_spj->jumlah_pajak ?? 'N/A',
                'harga_bersih'                  => $master_spj->harga_bersih ?? 'N/A',
                'admin_bank'                    => $master_spj->admin_bank ?? 'N/A',
                'bpjs_tk'                       => $master_spj->bpjs_tk ?? 'N/A',
                'bpjs_kes'                      => $master_spj->bpjs_kes ?? 'N/A',
                'kode_billingppn'               => $master_spj->kode_billingppn ?? 'N/A',
                'kode_billingpph22'             => $master_spj->kode_billingpph22 ?? 'N/A',
                'bruto'                         => $master_spj->bruto ?? 'N/A',
                'pembayaran'                    => $master_spj->pembayaran ?? 'N/A',
                'sisa_pembayaran'               => $master_spj->sisa_pembayaran ?? 'N/A',
                'nama_validasi_keuangan'        => $master_spj->nama_validasi_keuangan ?? 'N/A',
                'bulan_pembayaran'              => $master_spj->bulan_pembayaran ?? 'N/A',

            ];
        });

        // Return hasil
        return $result;
    }


    public function headings(): array
    {
        return [
            [
                'Report Data SPJ', // Baris pertama untuk judul kustom
            ],

            [
                'id_master_spj',
                'No Usulan Barang',
                'id_master_spj',
                'no_usulan_barang',
                'nama_pengusul_barang',
                'tgl_validasi_perencana',
                'tgl_validasi_kabag',
                'tgl_validasi_direktur',
                'tanggal_validasi_pengadaan',
                'status_hutang',
                'nama_perusahaan',
                'idpaket',
                'tgl_bast',
                'tgl_proses_faktur',
                'no_ba_hp',
                'no_ba_bp',
                'tanggal_faktur',
                'nomor_spk',
                'no_ba',
                'no_surat_pesanan',
                'no_dpa',
                'tgl_surat_pesanan',
                'jangka_waktu_pekerjaan',
                'tgl_proses_pemesanan',
                'tgl_barang_datang',
                'nama_pendukung_ppk',
                'keterangan_barang_datang',
                'tanggal_penyerahan_spj',
                'bulan_penyerahan_spj',
                'tanggal_revisi_spj',
                'harga_dasar',
                'ppn',
                'pph21',
                'pph22',
                'pph23',
                'pp05',
                'jumlah_pajak',
                'harga_bersih',
                'admin_bank',
                'bpjs_tk',
                'bpjs_kes',
                'kode_billingppn',
                'kode_billingpph22',
                'bruto',
                'pembayaran',
                'sisa_pembayaran',
                'nama_validasi_keuangan',
                'bulan_pembayaran',

            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Menggabungkan sel A1 sampai dengan Z1 untuk judul
                $event->sheet->mergeCells('A1:AP1');

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
