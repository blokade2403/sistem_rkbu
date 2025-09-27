<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriRkbu extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_kategori_rkbu';
    protected $fillable   =
    [
        'id_jenis_kategori_rkbu',
        'id_obyek_belanja',
        'kode_kategori_rkbu',
        'nama_kategori_rkbu'
    ];

    // Relasi ke ObyekBelanja
    public function obyek_belanja()
    {
        return $this->belongsTo(ObyekBelanja::class, 'id_obyek_belanja');
    }

    // Relasi ke SubKategoriRkbu
    public function sub_kategori_rkbus()
    {
        return $this->hasMany(SubKategoriRkbu::class, 'id_kategori_rkbu');
    }

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    public function sub_kategori_rkbu()
    {
        return $this->hasMany(SubKategoriRkbu::class, 'id_kategori_rkbu');
    }

    public function rkbus()
    {
        return $this->hasMany(Rkbu::class, 'id_kategori_rkbu');
    }

    public function obyekBelanjas()
    {
        return $this->hasMany(ObyekBelanja::class, 'id_kategori_rkbu');
    }
}
