<?php

namespace App\Models\MasterBackend\UserProfil;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\SettingUser\LevelUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\AdminPendukung;
use App\Models\MasterBackend\SettingUser\Pptk;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'id_ksp',
        'id_pejabat',
        'id_unit',
        'id_level',
        'id_fase',
        'username',
        'nama_lengkap',
        'nip_user',
        'email',
        'password',
        'status_user',
        'status_edit',
        'id_admin_pendukung_ppk',
        'id_pptk'
    ];

    // Definisikan relasi ke model Ksp
    public function ksp()
    {
        return $this->belongsTo(Ksp::class, 'id_ksp');
    }
    // Definisikan relasi ke model Ksp
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function levelUser()
    {
        return $this->belongsTo(LevelUser::class, 'id_level', 'id_level');
    }

    public function tahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran');
    }

    // Definisikan relasi ke model Ksp
    public function fase()
    {
        return $this->belongsTo(Fase::class, 'id_fase');
    }

    public function pejabat()
    {
        return $this->belongsTo(Pejabat::class, 'id_pejabat');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function admin_pendukungs()
    {
        return $this->belongsTo(AdminPendukung::class, 'id_admin_pendukung_ppk', 'id_admin_pendukung_ppk');
    }

    public function pptks()
    {
        return $this->belongsTo(Pptk::class, 'id_pptk', 'id_pptk');
    }
}
