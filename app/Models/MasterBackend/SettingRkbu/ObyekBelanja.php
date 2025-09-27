<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObyekBelanja extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_obyek_belanja';
    protected $fillable = [
        'kode_obyek_belanja',
        'nama_obyek_belanja',
        'id_jenis_kategori_rkbu',
    ];

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    public function jenisKategoriRkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    // Relasi ke KategoriRkbu
    public function kategori_rkbus()
    {
        return $this->hasMany(KategoriRkbu::class, 'id_obyek_belanja');
    }
}
