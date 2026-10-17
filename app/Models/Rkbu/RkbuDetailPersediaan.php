<?php

namespace App\Models\Rkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RkbuDetailPersediaan extends Model
{
    use HasUuids;

    protected $table = 'rkbu_detail_persediaans';

    protected $fillable = [
        'id',
        'rkbu_id',
        'nama_barang',
        'spesifikasi',
        'satuan_1',
        'jumlah_volume',
        'harga_satuan',
        'ppn',
        'total_anggaran',
        'rating',
        'link_ekatalog',
        'upload_file_1',
        'upload_file_2',
        'upload_file_3',
        'upload_file_4',
        'upload_file_5',
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
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
        'status_komponen',
    ];

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
