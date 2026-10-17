<?php

namespace App\Models\RkbuHistory;

use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\WorkFlow\Approval;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RkbuHistory extends Model
{
    use HasFactory, HasUuids;

    protected   $primaryKey = 'id';
    protected   $table      = 'rkbu_historys';

    protected $fillable = [
        'rkbu_id',
        'approval_id',
        'user_id',
        'aksi',
        'catatan',
        'file_path',
        'data_sebelum',
        'data_sesudah',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
    ];

    public function rkbu()
    {
        return $this->belongsTo(Rkbu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }


    public function getNamaSubKategoriRkbuAttribute()
    {
        $idSubKategori = $this->data_sebelum['id_sub_kategori_rkbu'] ?? null;

        if (!$idSubKategori) {
            return 'N/A';
        }

        $subKategori = SubKategoriRkbu::where('id_sub_kategori_rkbu', $idSubKategori)->first();

        return $subKategori ? $subKategori->nama_sub_kategori_rkbu : 'N/A';
    }
}
