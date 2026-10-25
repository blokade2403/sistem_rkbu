<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\MasterBackend\SettingBelanja\MasterSpj;
use App\Models\MasterBackend\SettingBelanja\SpjDetail;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use App\Models\MasterBackend\SettingUser\AdminPendukung;
use App\Models\MasterBackend\SettingUser\Pptk;
use App\Models\Rkbu\Rkbu;
use App\Models\WorkFlow\PositionSubKategoriRkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubKategoriRkbu extends Model
{
    use HasFactory, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'sub_kategori_rkbus';
    protected $primaryKey = 'id_sub_kategori_rkbu';
    protected $fillable = [
        'id_kategori_rkbu',
        'id_sub_kategori_rekening',
        'kode_sub_kategori_rkbu',
        'nama_sub_kategori_rkbu',
        'status',
    ];


    public function kategori()
    {
        return $this->belongsTo(
            KategoriRkbu::class,
            'id_kategori_rkbu',
            'id_kategori_rkbu'
        );
    }

    public function scopeForJenisKategori(Builder $query, string $namaJenisKategori): Builder
    {
        return $query->whereHas('kategori.jenis_kategori_rkbu', function (Builder $jenisKategoriQuery) use ($namaJenisKategori) {
            $jenisKategoriQuery->where('nama_jenis_kategori_rkbu', $namaJenisKategori);
        });
    }

    public function kategori_rkbu()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu');
    }

    public function kategoriRkbu()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu');
    }

    public function rkbus()
    {
        return $this->hasMany(Rkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function pendukungPpk()
    {
        return $this->belongsToMany(
            \App\Models\MasterBackend\UserProfil\User::class,
            'sub_kategori_pendukung_ppk',
            'sub_kategori_rkbu_id',
            'user_id',
            'id_sub_kategori_rkbu',
            'id'
        )->withTimestamps();
    }

    public function usulan_barangs()
    {
        return $this->hasMany(UsulanBarang::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function master_spj()
    {
        return $this->belongsTo(MasterSpj::class, 'id_master_spj');
    }

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'id_jenis_kategori_rkbu');
    }

    public function sub_kategori_rekening()
    {
        return $this->belongsTo(SubKategoriRekening::class, 'id_sub_kategori_rekening');
    }

    public function aktivitas()
    {
        return $this->hasOne(Aktivitas::class, 'id_sub_kategori_rkbu');
    }

    public function kegiatan()
    {
        return $this->hasOne(Kegiatan::class, 'id_sub_kategori_rkbu');
    }

    public function jenis_belanja()
    {
        return $this->belongsTo(JenisBelanja::class, 'id_jenis_belanja');
    }


    public function rekening_belanja2()
    {
        return $this->hasManyThrough(
            RekeningBelanja::class, // Model tujuan
            Rkbu::class, // Model perantara
            'id_sub_kategori_rkbu', // Foreign key di tabel rkbus
            'id_kode_rekening_belanja', // Foreign key di tabel rekening_belanjas
            'id_sub_kategori_rkbu', // Local key di tabel sub_kategori_rkbus
            'id_kode_rekening_belanja', // Local key di tabel rkbus
        );
    }

    public function positionSubKategoriRkbus()
    {
        return $this->hasMany(
            PositionSubKategoriRkbu::class,
            'id_sub_kategori_rkbu',
            'id_sub_kategori_rkbu',
        );
    }

    public function master_spj2()
    {
        return $this->hasMany(MasterSpj::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function realisasiAnggaran()
    {
        return $this->hasManyThrough(
            MasterSpj::class,
            SpjDetail::class,
            'id_rkbu', // Foreign key di tabel `spj_details`
            'id_spj', // Foreign key di tabel `master_spjs`
            'id_sub_kategori_rkbu', // Primary key di tabel ini
            'id_spj', // Local key di tabel `spj_details`
        )->where('master_spjs.keterangan', 'Sudah di Bayar');
    }
}
