<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TanggalPerencana extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = 'id_tanggal_perencanaan';
    protected $fillable = ['id_tahun_anggaran', 'fase_id', 'tanggal', 'no_dpa', 'kota', 'status'];
    protected $table = 'tanggal_perencanaans';

    public function TahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran', 'id');
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id', 'id');
    }
}
