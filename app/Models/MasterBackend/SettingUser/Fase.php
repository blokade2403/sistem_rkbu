<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fase extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'fases';
    protected $primaryKey = 'id_fase';
    protected $fillable = ['nama_fase'];
}
