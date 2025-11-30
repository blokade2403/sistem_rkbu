<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetSps extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'target_sps';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'bulan',
        'target',
        'nama_tahun_anggaran',
    ];

    public function TahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'id', 'id');
    }
}
