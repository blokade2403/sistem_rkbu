<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelUser extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'level_users';
    protected $primaryKey = 'id_level';
    protected $fillable = ['nama_level_user'];
}
