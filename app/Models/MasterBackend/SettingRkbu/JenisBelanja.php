<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisBelanja extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'jenis_belanjas';
    protected $primaryKey   = 'id_jenis_belanja';
    protected $fillable     = ['kode_jenis_belanja', 'nama_jenis_belanja'];

    // Relasi ke jenis_kategori_rkbus

    public function jenis_kategori_rkbus()
    {
        return $this->hasMany(JenisKategoriRkbu::class, 'id_jenis_belanja', 'id_jenis_belanja');
    }

    public function kategori_rkbus()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu');
    }

    public function sub_kategori_rkbus()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }

    public function obyek_belanja()
    {
        return $this->belongsTo(ObyekBelanja::class, 'id_obyek_belanja');
    }

    public function kategoriRkbus()
    {
        return $this->hasMany(KategoriRkbu::class, 'id_jenis_belanja', 'id');
    }

    // Relasi dengan obyek_belanja
    public function jenisKategoriRkbus()
    {
        return $this->hasMany(JenisKategoriRkbu::class, 'foreign_key', 'other_key');
    }
}
