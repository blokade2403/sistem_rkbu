<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RkbuHistory extends Model
{
    use HasFactory, HasUuids;

    protected   $primaryKey = 'id';
    protected   $table      = 'rkbu_historys';

    protected $fillable = [
        'id_rkbu',
        'id_jenis_kategori_rkbu',
        'id_user',
        'data_sebelum',
        'data_sesudah',
        'keterangan_status',
        'upload_file_5'
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function getNamaSubKategoriRkbuAttribute()
    {
        $idSubKategori = $this->data_sebelum['id_sub_kategori_rkbu'] ?? null;

        if (!$idSubKategori) {
            return 'N/A';
        }

        $subKategori = SubKategoriRkbu::where('id_sub_kategori_rkbu', $idSubKategori)->first();

        return $subKategori ? $subKategori->nama_sub_kategori_rkbu : 'N/A';
    }
}
