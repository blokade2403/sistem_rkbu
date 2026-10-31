<?php

namespace App\Models\Asset;

use App\Models\Asset\Penempatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\Rkbu\Rkbu;
use App\Models\UsulanBelanja\Spj;
use App\Models\UsulanBelanja\SpjDetail;
use App\Models\UsulanBelanja\UsulanBelanjaItem;

class Asset extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'assets';
    protected $primaryKey = 'id_asset';
    public $incrementing = false; // Jika `id_asset` bukan auto-increment
    protected $keyType = 'string'; // Jika `id_asset` bukan integer

    protected $fillable     =
    [
        'id_asset',
        'id_jenis_kategori_rkbu',
        'unit_id',
        'rkbu_id',
        'usulan_belanja_item_id',
        'spj_detail_id',
        'spj_id',
        'registered_by',
        'kode_asset',
        'nama_asset',
        'satuan',
        'spek',
        'harga_asset',
        'tahun_perolehan',
        'tgl_asset',
        'penyedia',
        'kondisi_asset',
        'pengguna_asset',
        'nip_pengguna',
        'jabatan_pengguna',
        'status_asset',
        'id_barang',
        'jumlah_asset',
        'total_anggaran_asset',
        'merk',
        'qrcode_path',
        'serial_number',
        'no_register',
        'type',
        'tgl_bpkb',
        'no_rangka',
        'no_mesin',
        'no_polisi',
        'kapitalisasi',
        'link_detail',
        'foto',
        'upload_sptjm',
        'id_penempatan',
        'hibah',
        'sumber_anggaran',
        'status_reklas_arb',
        'kategori_asset_bergerak',
        'lokasi_asset',
        'penanggung_jawab',
        'penanggung_jawab_user_id',
        'status_registrasi',

    ];

    protected $casts = [
        'harga_asset' => 'decimal:2',
        'jumlah_asset' => 'decimal:3',
        'total_anggaran_asset' => 'decimal:2',
        'tahun_perolehan' => 'integer',
        'tgl_asset' => 'date',
        'tgl_bpkb' => 'date',
    ];

    public function JenisKategoriRkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    public function Penempatan()
    {
        return $this->belongsTo(Penempatan::class, 'id_penempatan');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }

    public function usulanItem()
    {
        return $this->belongsTo(UsulanBelanjaItem::class, 'usulan_belanja_item_id');
    }

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function spjDetail()
    {
        return $this->belongsTo(SpjDetail::class, 'spj_detail_id');
    }

    public function histories()
    {
        return $this->hasMany(AssetHistory::class, 'asset_id', 'id_asset');
    }
}
