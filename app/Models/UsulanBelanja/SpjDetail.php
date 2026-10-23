<?php

namespace App\Models\UsulanBelanja;

use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpjDetail extends Model
{
    use HasUuids;

    protected $table = 'spj_details';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'rkbu_id',
        'spj_id',
        'usulan_belanja_id',
        'usulan_belanja_item_id',
        'volume',
    ];

    public function spj(): BelongsTo
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function usulanBelanja(): BelongsTo
    {
        return $this->belongsTo(UsulanBelanja::class, 'usulan_belanja_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(UsulanBelanjaItem::class, 'usulan_belanja_item_id');
    }

    public function rkbu(): BelongsTo
    {
        return $this->belongsTo(Rkbu::class, 'rkbu_id');
    }
}
