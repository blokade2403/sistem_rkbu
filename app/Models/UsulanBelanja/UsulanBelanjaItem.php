<?php

namespace App\Models\UsulanBelanja;

use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanBelanjaItem extends Model
{
    use HasUuids;

    protected $table = 'usulan_belanja_items';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'usulan_belanja_id',
        'rkbu_id',
        'detail_type',
        'detail_id',
        'harga_satuan',
        'volume',
        'ppn',
        'spesifikasi',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'volume' => 'decimal:3',
            'ppn' => 'decimal:2',
        ];
    }

    public function usulanBelanja(): BelongsTo
    {
        return $this->belongsTo(UsulanBelanja::class, 'usulan_belanja_id');
    }

    public function rkbu(): BelongsTo
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
