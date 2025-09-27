<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Ksp;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fungsional extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_fungsional';
    protected $fillable = ['jabatan_fungsional', 'id'];

    protected $table = 'fungsionals';
    public function ksps()
    {
        return $this->hasMany(Ksp::class, 'id_fungsional');
    }
}
