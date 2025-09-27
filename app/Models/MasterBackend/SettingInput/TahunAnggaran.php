<?php

namespace App\Models\MasterBackend\SettingInput;

use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Pptk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TahunAnggaran extends Model
{
    use HasFactory, HasUuids;

    public function pptks()
    {
        return $this->hasMany(Pptk::class, 'id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'id_fase');
    }

    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_tahun_anggaran',
        'status',
        'fase_tahun',
    ];
}
