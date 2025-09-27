<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Pejabat;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_jabatan';
    protected $fillable = ['nama_jabatan', 'id_jabatan'];

    protected $table = 'jabatans';
    public function pejabats()
    {
        return $this->hasMany(Pejabat::class, 'id_jabatan');
    }
}
