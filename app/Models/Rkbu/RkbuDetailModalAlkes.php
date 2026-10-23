<?php

namespace App\Models\Rkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RkbuDetailModalAlkes extends Model
{
    use HasUuids;

    protected $table = 'rkbu_detail_modal_alkes';

    protected $fillable = [
        'id',
        'rkbu_id',
        'nama_barang',
        'spesifikasi',
        'volume_1',
        'satuan_1',
        'volume_2',
        'satuan_2',
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
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
        'status_komponen',
        'standar_kebutuhan',
        'eksisting',
        'kondisi_baik',
        'kondisi_rusak_berat',
    ];

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
