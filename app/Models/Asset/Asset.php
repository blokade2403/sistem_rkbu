<?php

namespace App\Models\Asset;

use App\Models\Asset\Penempatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;

class Asset extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'assets';
    protected $primaryKey = 'id_asset';
    public $incrementing = false; // Jika `id_asset` bukan auto-increment
    protected $keyType = 'string'; // Jika `id_asset` bukan integer

    protected $fillable     =
    [
        'id_jenis_kategori_rkbu',
        'kode_asset',
        'nama_asset',
        'satuan',
        'spek',
        'harga_asset',
        'tahun_perolehan',
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

    ];

    public function JenisKategoriRkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    public function Penempatan()
    {
        return $this->belongsTo(Penempatan::class, 'id_penempatan');
    }
}
