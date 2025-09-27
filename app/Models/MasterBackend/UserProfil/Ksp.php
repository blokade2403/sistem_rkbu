<?php

namespace App\Models\MasterBackend\UserProfil;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Pejabat;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MasterBackend\SettingUser\Fungsional;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ksp extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ksps';
    // Relasi dengan tabel Jabatan
    public function pejabat()
    {
        return $this->belongsTo(Pejabat::class, 'id_pejabat');
    }

    // Relasi dengan tabel Fungsional
    public function fungsional()
    {
        return $this->belongsTo(Fungsional::class, 'id_fungsional');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'id_ksp');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_ksp', 'id_ksp');
    }

    protected $primaryKey = 'id_ksp';
    protected $fillable = [
        'id_fungsional',
        'id_pejabat',
        'nama_ksp',
        'nip_ksp',
        'status',
    ];
}
