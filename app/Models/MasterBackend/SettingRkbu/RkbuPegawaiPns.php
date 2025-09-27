<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\ObyekBelanja;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;

class RkbuPegawaiPns extends Model
{
    use HasFactory, HasUuids;

    protected $table        = 'rkbus';
    protected $primaryKey   = 'id_rkbu';

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }

    public function sub_kategori_rekening()
    {
        return $this->belongsTo(SubKategoriRekening::class, 'id_sub_kategori_rekening');
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
}
