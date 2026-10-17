<?php

namespace App\Models\Rkbu;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\RkbuHistory\RkbuHistory;
use App\Models\WorkFlow\WorkflowInstance;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Rkbu extends Model
{
    use HasUuids;

    protected $table = 'rkbus';

    protected $fillable = [
        'id',
        'nomor_rkbu',
        'tahun_anggaran_id',
        'unit_id',
        'sumber_dana_id',
        'jenis_belanja_id',
        'jenis_kategori_rkbu_id',
        'sub_kategori_rkbu_id',
        'sub_kegiatan_id',
        'created_by',
        'workflow_instance_id',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(RkbuDetailBarjas::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->hasMany(RkbuHistory::class);
    }

    public function detailBarjas()
    {
        return $this->hasMany(
            RkbuDetailBarjas::class,
            'rkbu_id'
        );
    }

    public function detailPegawai()
    {
        return $this->hasMany(RkbuDetailPegawai::class, 'rkbu_id');
    }

    public function detailPersediaan()
    {
        return $this->hasMany(RkbuDetailPersediaan::class, 'rkbu_id');
    }

    public function detailModal()
    {
        return $this->hasMany(RkbuDetailModal::class, 'rkbu_id');
    }

    public function sumberDana()
    {
        return $this->belongsTo(
            SumberDana::class,
            'sumber_dana_id',
            'id_sumber_dana'
        );
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(
            SubKategoriRkbu::class,
            'sub_kategori_rkbu_id',
            'id_sub_kategori_rkbu'
        );
    }

    public function subKegiatan()
    {
        return $this->belongsTo(
            SubKegiatan::class,
            'sub_kegiatan_id',
            'id_sub_kegiatan'
        );
    }

    public function subKategori()
    {
        return $this->belongsTo(
            SubKategoriRkbu::class,
            'sub_kategori_rkbu_id',
            'id_sub_kategori_rkbu'
        );
    }

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    public function workflowInstance()
    {
        return $this->belongsTo(
            WorkflowInstance::class,
            'workflow_instance_id',
            'id'
        );
    }

    public function tahunAnggaran()
    {
        return $this->belongsTo(
            TahunAnggaran::class,
            'tahun_anggaran_id'
        );
    }

    public function jenisBelanja()
    {
        return $this->belongsTo(
            JenisBelanja::class,
            'jenis_belanja_id',
            'id_jenis_belanja'
        );
    }
}
