<?php

namespace App\Models\MasterBackend\SettingInput;

use App\Models\MasterBackend\UserProfil\User;
use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaguAnggaranHistory extends Model
{
    use HasUuids;

    protected $table = 'pagu_anggaran_histories';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pagu_anggaran_id',
        'tahun_anggaran_id',
        'id_kode_rekening_belanja',
        'user_id',
        'rkbu_id',
        'usulan_belanja_id',
        'fase_urutan',
        'jenis_perubahan',
        'alasan',
        'lampiran',
        'nilai_sebelum',
        'nilai_sesudah',
        'selisih',
        'detail_sebelum',
        'detail_sesudah',
    ];

    protected function casts(): array
    {
        return [
            'nilai_sebelum' => 'decimal:2',
            'nilai_sesudah' => 'decimal:2',
            'selisih' => 'decimal:2',
            'detail_sebelum' => 'array',
            'detail_sesudah' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rkbu(): BelongsTo
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
