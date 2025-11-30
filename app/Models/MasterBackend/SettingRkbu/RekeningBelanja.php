<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingInput\Anggaran;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekeningBelanja extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_kode_rekening_belanja';
    protected $fillable =
    [
        'id_aktivitas',
        'kode_rekening_belanja',
        'nama_rekening_belanja'
    ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function anggarans()
    {
        return $this->hasMany(Anggaran::class, 'id_kode_rekening_belanja');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rekening', 'id_sub_kategori_rekening');
    }

    public function rkbus()
    {
        return $this->hasMany(Rkbu::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }
}
