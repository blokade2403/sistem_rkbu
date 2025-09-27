<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;

class Pptk extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pptks';

    public function tahun_anggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran');
    }

    public function jabatans()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rekening', 'id_sub_kategori_rekening');
    }

    protected $primaryKey = 'id_pptk';
    protected $fillable = ['nama_pptk', 'nip_pptk', 'id_tahun_anggaran', 'status'];
}
