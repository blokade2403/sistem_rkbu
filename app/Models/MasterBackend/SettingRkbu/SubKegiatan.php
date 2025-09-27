<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $table        = 'sub_kegiatans';
    protected $primaryKey   = 'id_sub_kegiatan';
    public $incrementing    = false;
    protected $keyType      = 'string';

    protected $fillable = [
        'id_kegiatan',
        'kode_sub_kegiatan',
        'nama_sub_kegiatan',
        'id_sumber_dana',
        'tujuan_sub_kegiatan',
        'indikator_sub_kegiatan',
    ];

    public function sumber_danas()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana', 'id_sumber_dana');
    }

    public function aktivitas()
    {
        return $this->hasOne(Aktivitas::class, 'id_sub_kegiatan', 'id_sub_kegiatan');
    }

    public function aktivitass()
    {
        return $this->hasMany(Aktivitas::class, 'id_sub_kegiatan', 'id_sub_kegiatan');
    }

    public function rekeningBelanja()
    {
        return $this->hasManyThrough(
            RekeningBelanja::class,
            Aktivitas::class,
            'id_sub_kegiatan', // Foreign key di aktivitas
            'id_aktivitas',    // Foreign key di rekening_belanja
            'id_sub_kegiatan', // Local key di sub_kegiatans
            'id_aktivitas'     // Local key di aktivitas
        );
    }

    public function rkbus()
    {
        return $this->hasManyThrough(
            Rkbu::class,
            RekeningBelanja::class,
            'id_kode_rekening_belanja', // Foreign key di rekening_belanja
            'id_kode_rekening_belanja', // Foreign key di rkbus
            'id_sub_kegiatan',          // Local key di sub_kegiatans
            'id_kode_rekening_belanja'  // Local key di rekening_belanja
        );
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }
}
