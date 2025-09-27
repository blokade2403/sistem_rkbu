<?php

namespace App\Models\MasterBackend\SettingBelanja;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;

class UsulanBarangDetail extends Model
{
    use HasFactory, HasUuids;

    protected   $table          = 'usulan_barang_details';
    protected   $primaryKey     = 'id_usulan_barang_detail';
    public      $timestamps     = true;

    protected $fillable = [
        'id_usulan_barang',
        'id_rkbu',
        'id_sub_kategori_rkbu',
        'harga_barang',
        'ppn',
        'total_ppn',
        'jumlah_usulan_barang',
        'total_anggaran_usulan_barang',
        'rata2_pemakaian',
        'pengkali',
        'sisa_stok',
        'stok_minimal',
        'buffer_stok',
        'jumlah_vol_rkbu',
        'vol_1_detail',
        'satuan_1_detail',
        'satuan_2_detail',
        'spek_detail',
        'created_by',
    ];

    public function usulan_barang()
    {
        return $this->belongsTo(UsulanBarang::class, 'id_usulan_barang');
    }

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class, 'id_rkbu');
    }

    public function rkbus()
    {
        return $this->belongsTo(Rkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function spjDetails()
    {
        return $this->hasMany(SpjDetail::class, 'id_usulan_barang_detail', 'id_usulan_barang_detail');
    }
}
