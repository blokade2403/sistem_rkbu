<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class Komponen extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_komponen';
    protected $fillable = [
        'id_komponen',
        'id_jenis_kategori_rkbu',
        'kode_barang',
        'kode_komponen',
        'nama_barang',
        'satuan',
        'spek',
        'harga_barang'
    ];

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }
}
