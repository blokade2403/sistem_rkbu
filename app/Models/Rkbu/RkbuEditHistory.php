<?php

namespace App\Models\Rkbu;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RkbuEditHistory extends Model
{
    use HasUuids;

    protected $table = 'rkbu_edit_histories';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'rkbu_id',
        'user_id',
        'approval_id',
        'fase_urutan',
        'alasan',
        'lampiran',
        'nilai_sebelum',
        'nilai_sesudah',
        'selisih',
        'header_sebelum',
        'header_sesudah',
        'detail_sebelum',
        'detail_sesudah',
    ];

    protected $casts = [
        'nilai_sebelum' => 'decimal:2',
        'nilai_sesudah' => 'decimal:2',
        'selisih' => 'decimal:2',
        'header_sebelum' => 'array',
        'header_sesudah' => 'array',
        'detail_sebelum' => 'array',
        'detail_sesudah' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
