<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UraianDua extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_uraian_2';
    protected $fillable = ['nama_uraian_2'];
}
