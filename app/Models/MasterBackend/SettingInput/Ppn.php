<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ppn'; // sesuaikan dengan nama tabel
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'ppn',
        'status',
    ];
}
