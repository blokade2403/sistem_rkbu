<?php

namespace App\Models;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DokumenPerencanaan extends Model
{
    use HasUuids;

    protected $fillable = ['tahun_anggaran_id', 'fase_id', 'judul', 'keterangan', 'file_path', 'file_name', 'created_by'];

    public function tahunAnggaran() { return $this->belongsTo(TahunAnggaran::class, 'tahun_anggaran_id'); }
    public function fase() { return $this->belongsTo(Fase::class, 'fase_id'); }
}
