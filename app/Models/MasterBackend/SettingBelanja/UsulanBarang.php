<?php

namespace App\Models\MasterBackend\SettingBelanja;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\Ksp;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MasterBackend\SettingInput\UraianDua;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingBelanja\MasterSpj;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\StatusValidasi;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use App\Models\MasterBackend\SettingBelanja\UsulanBarangDetail;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingUser\Pptk;
use App\Models\MasterBackend\SettingUser\Unit;

class UsulanBarang extends Model
{
    use HasFactory, HasUuids;

    protected $table      = 'usulan_barangs';
    protected $primaryKey = 'id_usulan_barang';

    protected $fillable = [
        'id_usulan_barang',
        'id_sumber_dana',
        'status_validasi_pengadaan',
        'keterangan_validasi_pengadaan',
        'tanggal_validasi_pengadaan',
        // Tambahkan kolom lain yang boleh diisi secara massal
    ];

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu');
    }



    public function kategori_rkbu()
    {
        return $this->belongsTo(KategoriRkbu::class, 'id_kategori_rkbu');
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

    public function uraian1()
    {
        return $this->belongsTo(UraianSatu::class, 'id_uraian_1');
    }

    public function uraian2()
    {
        return $this->belongsTo(UraianDua::class, 'id_uraian_2');
    }

    public function usulan_barang_detail()
    {
        return $this->belongsTo(UsulanBarangDetail::class, 'id_usulan_barang_detail');
    }

    public function usulanBarangDetails()
    {
        return $this->hasMany(UsulanBarangDetail::class, 'id_usulan_barang', 'id_usulan_barang');
    }

    public function ksp()
    {
        return $this->belongsTo(Ksp::class, 'id_ksp');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function validPerencana()
    {
        return $this->belongsTo(User::class, 'nama_valid_perencana', 'id_user');
    }

    public function ValidPptk()
    {
        return $this->belongsTo(User::class, 'nama_valid_pptk', 'id_user');
    }

    public function validRka()
    {
        return $this->belongsTo(User::class, 'nama_valid_rka', 'id_user');
    }

    public function validDirektur()
    {
        return $this->belongsTo(User::class, 'nama_valid_direktur', 'id_user');
    }

    public function masterSpj()
    {
        return $this->hasMany(MasterSpj::class, 'id_usulan_barang', 'id_usulan_barang');
    }

    public function rkbus()
    {
        return $this->hasManyThrough(
            Rkbu::class,
            SubKategoriRkbu::class,
            'id_sub_kategori_rkbu', // Foreign key di sub_kategori_rkbus
            'id_sub_kategori_rkbu', // Foreign key di rkbus
            'id_sub_kategori_rkbu', // Local key di usulan_barangs
            'id_sub_kategori_rkbu'  // Local key di sub_kategori_rkbus
        );
    }
}
