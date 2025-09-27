<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminPendukung extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_admin_pendukung_ppk';
    protected $fillable = [
        'nama_pendukung_ppk',
        'jabatan_pendukung_ppk',
        'nrk',
        'id_pejabat_pengadaan',
        'id_pptk',
        'id_user',
    ];

    public function pejabat_pengadaan()
    {
        return $this->belongsTo(PejabatPengadaan::class, 'id_pejabat_pengadaan');
    }

    public function admin_pendukung_ppk()
    {
        return $this->hasMany(AdminPendukung::class, 'id_admin_pendukung_ppk');
    }

    public function pptk()
    {
        return $this->belongsTo(Pptk::class, 'id_pptk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
