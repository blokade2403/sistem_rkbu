<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Ksp;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;

class RkbuModalKantor extends Model
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
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
        'status_komponen',
        'standar_kebutuhan',
        'eksisting',
        'kondisi_baik',
        'kondisi_rusak_berat',
    ];

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }

    public function rekening_belanjas()
    {
        return $this->belongsTo(RekeningBelanja::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
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

    public function status_validasi_rka()
    {
        return $this->belongsTo(StatusValidasiRka::class, 'id_status_validasi_rka');
    }

    public function status_validasi()
    {
        return $this->belongsTo(StatusValidasi::class, 'id_status_validasi');
    }

    public function kategori_rkbus()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu', 'id_kategori_rkbu');
    }

    public function obyek_belanjas()
    {
        return $this->belongsTo(ObyekBelanja::class, 'id_obyek_belanja', 'id_obyek_belanja');
    }

    public function jenis_kategori_rkbus()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu', 'id_jenis_kategori_rkbu');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }


    public function Ksp()
    {
        return $this->belongsTo(Ksp::class, 'id_ksp');
    }
}
