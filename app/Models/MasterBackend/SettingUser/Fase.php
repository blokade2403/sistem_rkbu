<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;

class Fase extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'fases';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_fase', 'urutan'];

    public function tahunAnggarans()
    {
        return $this->hasMany(TahunAnggaran::class, 'fase_id', 'id');
    }
}
