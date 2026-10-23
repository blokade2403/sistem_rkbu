<?php

namespace App\Models\UsulanBelanja;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spj extends Model
{
    use HasUuids;

    protected $table = 'spjs';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'usulan_belanja_id',
        'user_id',
        'no_usulan_belanja',
        'nomor_urut',
        'status_spj',
    ];

    public function usulanBelanja(): BelongsTo
    {
        return $this->belongsTo(UsulanBelanja::class, 'usulan_belanja_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SpjDetail::class, 'spj_id');
    }
}
