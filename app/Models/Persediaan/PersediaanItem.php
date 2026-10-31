<?php

namespace App\Models\Persediaan;

use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PersediaanItem extends Model
{
    use HasUuids;

    protected $table = 'persediaan_items';

    protected $fillable = [
        'kode_barang', 'unit_id', 'sub_kategori_rkbu_id', 'usulan_belanja_item_id', 'nama_barang',
        'spesifikasi', 'satuan', 'harga_satuan', 'ppn', 'stok',
    ];

    protected $casts = ['stok' => 'decimal:3', 'harga_satuan' => 'decimal:2', 'ppn' => 'decimal:2'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'sub_kategori_rkbu_id', 'id_sub_kategori_rkbu');
    }

    public function getNilaiPersediaanAttribute(): float
    {
        return (float) $this->stok * (float) $this->harga_satuan * (1 + (float) $this->ppn / 100);
    }

    public function transactions()
    {
        return $this->hasMany(PersediaanTransaction::class, 'persediaan_item_id');
    }
}
