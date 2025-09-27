<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivitas extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_aktivitas';
    protected $fillable = [
        'kode_aktivitas',
        'nama_aktivitas',
        'id_program',
        'id_sub_kegiatan'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function sub_kegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'id_sub_kegiatan', 'id_sub_kegiatan');
    }

    public function rekening_belanja()
    {
        return $this->hasMany(RekeningBelanja::class, 'id_aktivitas');
    }

    public function rekening_belanjas()
    {
        return $this->hasMany(RekeningBelanja::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function kodeRekeningBelanja()
    {
        return $this->hasMany(RekeningBelanja::class, 'id_aktivitas');
    }
    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
    public function sub_kegiatans()
    {
        return $this->hasMany(SubKegiatan::class, 'id_aktivitas', 'id');
    }
}
