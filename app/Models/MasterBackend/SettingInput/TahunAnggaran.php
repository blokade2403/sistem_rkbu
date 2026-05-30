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
    protected $table = 'tahun_anggarans';
    protected $fillable = ['id', 'nama_tahun_anggaran', 'fase_id', 'status'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id', 'id');
    }
}
