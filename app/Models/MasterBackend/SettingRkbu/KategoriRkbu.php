<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(
            JenisKategoriRkbu::class,
            'id_jenis_kategori_rkbu',
            'id_jenis_kategori_rkbu'
        );
    }

    // Relasi ke ObyekBelanja
    public function obyek_belanja()
    {
        return $this->belongsTo(ObyekBelanja::class, 'id_obyek_belanja');
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
