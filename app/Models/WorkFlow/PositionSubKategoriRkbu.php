<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingUser\Position;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionSubKategoriRkbu extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'position_sub_kategori_rkbus';

    protected $fillable = ['id_sub_kategori_rkbu', 'position_id'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function subKategoriRkbu()
    {
        return $this->belongsTo(
            SubKategoriRkbu::class,
            'id_sub_kategori_rkbu',
            'id_sub_kategori_rkbu',
        );
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
