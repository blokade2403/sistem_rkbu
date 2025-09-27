<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penempatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penempatans';
    protected $primaryKey = 'id_penempatan';

    protected $fillable = [
        'lokasi_barang',
        'tempat_lokasi',
        'gedung',
        'penanggung_jawab',
    ];
}
