<?php

namespace App\Models\Rkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RkbuDetailPegawai extends Model
{
    use HasUuids;

    protected $table = 'rkbu_detail_pegawais';

    protected $fillable = [
        'id',
        'rkbu_id',
        'jumlah_volume',
        'total_anggaran',
        'nama_pegawai',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'jabatan',
        'status_kawin',
        'nomor_kontrak',
        'tmt_pegawai',
        'bulan_tmt',
        'tahun_tmt',
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
    ];

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
