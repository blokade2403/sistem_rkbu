<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;

class PejabatPengadaan extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_pejabat_pengadaan';
    protected $fillable = [
        'id_tahun_anggaran',
        'id_ppk_keuangan',
        'nama_ppk',
        'nama_ppbj',
        'nip_ppk',
        'nip_ppbj',
        'status',
        'nama_pengurus_barang',
        'nip_pengurus_barang',
        'nama_direktur',
        'nip_direktur',
        'nama_bendahara',
        'nip_bendahara'
    ];

    public function TahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran');
    }

    public function ppk_keuangan()
    {
        return $this->belongsTo(PpkKeuangan::class, 'id_ppk_keuangan');
    }

    public function admin_pendukung()
    {
        return $this->hasMany(AdminPendukung::class, 'id_pejabat_pengadaan');
    }
}
