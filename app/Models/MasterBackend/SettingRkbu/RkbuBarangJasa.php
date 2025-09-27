<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class RkbuBarangJasa extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'rkbus';
    protected $primaryKey   = 'id_rkbu';
    protected $fillable     =
    [
        'id_sub_kategori_rkbu',
        'id_sub_kategori_rekening',
        'id_kode_rekening_belanja',
        'id_status_validasi',
        'id_user',
        'nama_barang',
        'vol_1',
        'satuan_1',
        'vol_2',
        'satuan_2',
        'spek',
        'jumlah_vol',
        'harga_satuan',
        'ppn',
        'total_anggaran',
        'rating',
        'nama_tahun_anggaran',
        'penempatan',
        'link_ekatalog',
        'upload_file1',
        'upload_file2',
        'upload_file3',
        'upload_file4',
        'upload_file5',
        'keterangan_status',
        'status_komponen',
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
    ];

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function spjDetails()
    {
        return $this->hasMany(SpjDetail::class, 'id_rkbu', 'id_rkbu');
    }

    public function rekening_belanjas()
    {
        return $this->belongsTo(RekeningBelanja::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas');
    }

    public function sub_kegiatans()
    {
        return $this->belongsTo(SubKegiatan::class, 'id_sub_kegiatan');
    }

    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function sumber_dana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }

    public function status_validasi()
    {
        return $this->belongsTo(StatusValidasi::class, 'id_status_validasi');
    }

    public function status_validasi_rka()
    {
        return $this->belongsTo(StatusValidasiRka::class, 'id_status_validasi_rka');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
