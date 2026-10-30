<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Penempatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penempatans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'lokasi_barang',
        'tempat_lokasi',
        'gedung',
        'penanggung_jawab',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $penempatan): void {
            $id = $penempatan->id_penempatan ?: (string) Str::uuid();
            $penempatan->id_penempatan = $id;
            $penempatan->id = $id;
        });
    }
}
