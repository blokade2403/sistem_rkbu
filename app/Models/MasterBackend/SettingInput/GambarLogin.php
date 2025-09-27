<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GambarLogin extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gambar_logins';
    protected $primaryKey = 'id_gambar_login';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_gambar_login',
        'gambar_login',
        'status_gambar',
        'tahun_anggaran'
    ];
}
