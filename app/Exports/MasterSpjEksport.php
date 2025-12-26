<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\MasterBackend\SettingBelanja\MasterSpj;

class MasterSpjEksport implements FromCollection, WithHeadings, WithEvents
{

    public function collection()
    {
        // Mengambil session id_user dan tahun_anggaran
        $idUser = session('id_user');
        $tahunAnggaran = session('tahun_anggaran');

        // Query master_spj berdasarkan session id_user dan tahun_anggaran
        $data = MasterSpj::join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('rkbus', 'spj_details.id_rkbu', '=', 'rkbus.id_rkbu')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang_detail', '=', 'spj_details.id_usulan_barang_detail')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            // ->where('master_spjs.id_master_spj', '77940797-f300-4005-84e6-69a9a38c8605')
            ->select(
                'master_spjs.idpaket',
                'master_spjs.bruto',
                'master_spjs.pembayaran',
                'master_spjs.sisa_pembayaran',
                'master_spjs.tahun_anggaran',
                'master_spjs.tanggal_faktur',
                'master_spjs.nomor_spk',
                'master_spjs.rincian_belanja',
                'master_spjs.harga_dasar',
                'master_spjs.tanggal_penyerahan_spj',
                'master_spjs.bulan_penyerahan_spj',
                'master_spjs.tanggal_revisi_spj',
                'master_spjs.tanggal_input_listing',
                'master_spjs.ppn',
                'master_spjs.pph21',
                'master_spjs.pph22',
                'master_spjs.pph23',
                'master_spjs.pp05',
                'master_spjs.jumlah_pajak',
                'master_spjs.harga_bersih',
                'master_spjs.admin_bank',
                'master_spjs.bpjs_tk',
                'master_spjs.bpjs_kes',
                'master_spjs.keterangan',
                'master_spjs.nama_validasi_keuangan',
                'master_spjs.bulan_pembayaran',
                'master_spjs.tanggal_pembayaran',
                'master_spjs.kode_billingppn',
                'master_spjs.kode_billingpph22',
                'master_spjs.no_ba',
                'master_spjs.no_surat_pesanan',
                'master_spjs.tgl_surat_pesanan',
                'master_spjs.no_dpa',
                'master_spjs.jangka_waktu_pekerjaan',
                'master_spjs.keterangan_barang_datang',
                'master_spjs.keterangan_barang_datang_parsial_1',
                'master_spjs.keterangan_barang_datang_parsial_2',
                'master_spjs.keterangan_barang_datang_parsial_3',
                'master_spjs.tgl_proses_pemesanan',
                'master_spjs.tgl_barang_datang',
                'master_spjs.tgl_barang_datang_parsial_1',
                'master_spjs.tgl_barang_datang_parsial_2',
                'master_spjs.tgl_barang_datang_parsial_3',
                'master_spjs.tgl_bast',
                'master_spjs.tgl_proses_faktur',
                'master_spjs.tgl_verif',
                'master_spjs.no_ba_hp',
                'master_spjs.no_ba_bp',
                'master_spjs.tgl_kwitansi',
                'master_spjs.status_proses_pesanan',
                'master_spjs.status_proses_pengiriman_barang',
                'master_spjs.status_proses_bast',
                'master_spjs.status_proses_tukar_faktur',
                'master_spjs.status_verifikasi_ppk',
                'master_spjs.status_verifikasi_ppbj',
                'master_spjs.status_verifikasi_pptk',
                'master_spjs.status_verifikasi_verifikator',
                'master_spjs.status_verifikasi_ppk_keuangan',
                'master_spjs.status_verifikasi_direktur',
                'master_spjs.status_pembayaran',
                'master_spjs.tgl_verif_ppbj',
                'master_spjs.tgl_verif_pptk',
                'master_spjs.tgl_verif_verifikator',
                'master_spjs.tgl_verif_ppk_keuangan',
                'master_spjs.tgl_verif_direktur',
                'master_spjs.status_serah_terima_bendahara',
                'master_spjs.bukti_bayar',
                'master_spjs.ket_verif_ppk',
                'master_spjs.ket_verif_ppbj',
                'master_spjs.ket_verif_verifikator',
                'master_spjs.ket_verif_ppk_keuangan',
                'master_spjs.ket_verif_direktur',
                'master_spjs.ket_verif_pptk',
                'master_spjs.tgl_revisi_spj_ppk_keuangan',
                'master_spjs.tgl_revisi_spj_direktur',
                'master_spjs.ket_bendahara',
                'master_spjs.tgl_verif_pengurus_barang',
                'master_spjs.keterangan_verif_pengurus_barang',
                'master_spjs.keterangan_verif_pengurus_barang_parsial_1',
                'master_spjs.keterangan_verif_pengurus_barang_parsial_2',
                'master_spjs.keterangan_verif_pengurus_barang_parsial_3',
                'master_spjs.status_verifikasi_pengurus_barang',
                'master_spjs.status_verifikasi_pengurus_barang_parsial_1',
                'master_spjs.status_verifikasi_pengurus_barang_parsial_2',
                'master_spjs.status_verifikasi_pengurus_barang_parsial_3',
                'master_spjs.ket_verif_bendahara',
                'master_spjs.tgl_verif_bendahara',
                'master_spjs.status_hutang',
                'spj_details.*',
                'usulan_barangs.no_usulan_barang',
                'usulan_barangs.nama_pengusul_barang',
                'spj_details.id_usulan_barang_detail',
                // 'master_spjs.*',
                'spj_details.id_rkbu',
                'rkbus.nama_barang',
                'rkbus.nama_pegawai',
                'usulan_barang_details.jumlah_usulan_barang',
                'usulan_barang_details.harga_barang',
                'usulan_barang_details.total_ppn',
                'usulan_barang_details.total_anggaran_usulan_barang',
                'usulan_barang_details.spek_detail',
                'usulan_barang_details.status_usulan_barang_detail',
                // 'spj_details.id_usulan_barang_detail'
            )
            ->distinct()
            ->get();

        // dd($data);

        // dd($data);
        // Menampilkan semua id_master_spj
        $result = $data->map(function ($master_spj) {
            return [
                'id_master_spj'                 => $master_spj->id_master_spj,
                'id_spj'                        => $master_spj->id_spj ?? 'N/A',
                'id_usulan_barang_detail'       => $master_spj->id_usulan_barang_detail ?? 'N/A',
                'no_usulan_barang'              => $master_spj->usulanBarang->no_usulan_barang ?? 'N/A',
                'nama_pengusul_barang'          => $master_spj->usulanBarang->nama_pengusul_barang ?? 'N/A',
                'tgl_validasi_pptk'             => $master_spj->usulanBarang->tgl_validasi_pptk ?? 'N/A',
                'tgl_validasi_kabag'            => $master_spj->usulanBarang->tgl_validasi_kabag ?? 'N/A',
                'tgl_validasi_direktur'         => $master_spj->usulanBarang->tgl_validasi_direktur ?? 'N/A',
                'tanggal_validasi_pengadaan'    => $master_spj->usulanBarang->tanggal_validasi_pengadaan ?? 'N/A',
                'nama_barang'                   => $master_spj->RkbusAll->nama_barang ?? 'N/A',
                'nama_pegawai'                   => $master_spj->RkbusAll->nama_pegawai ?? 'N/A',
                'jumlah_usulan_barang'          => $master_spj->usulan_barang_detail->jumlah_usulan_barang ?? 'N/A',
                'harga_barang'                  => $master_spj->usulan_barang_detail->harga_barang ?? 'N/A',
                'total_ppn'                     => $master_spj->usulan_barang_detail->total_ppn ?? 'N/A',
                'total_anggaran_usulan_barang'  => $master_spj->usulan_barang_detail->total_anggaran_usulan_barang ?? 'N/A',
                'spek_detail'                   => $master_spj->usulan_barang_detail->spek_detail ?? 'N/A',
                'status_usulan_barang_detail'   => $master_spj->usulan_barang_detail->status_usulan_barang_detail ?? 'N/A',
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
                'id_spj',
                'id_usulan_barang_detail',
                'No Usulan Barang',
                'nama_pengusul_barang',
                'tgl_validasi_perencana',
                'tgl_validasi_kabag',
                'tgl_validasi_direktur',
                'tanggal_validasi_pengadaan',
                'nama_barang',
                'nama_pegawai',
                'jumlah_usulan_barang',
                'harga_barang',
                'total_ppn',
                'total_anggaran_usulan_barang',
                'spek_detail',
                'status_usulan_barang_detail',
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
