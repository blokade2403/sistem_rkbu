<?php

namespace App\Models\MasterBackend\SettingInput;

use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaguAnggaran extends Model
{
    use HasUuids;

    protected $table = 'pagu_anggarans';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tahun_anggaran_id',
        'id_kode_rekening_belanja',
        'jenis_sumber_dana',
        'jumlah_pagu',
    ];

    protected function casts(): array
    {
        return ['jumlah_pagu' => 'decimal:2'];
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class, 'tahun_anggaran_id');
    }

    public function rekeningBelanja(): BelongsTo
    {
        return $this->belongsTo(RekeningBelanja::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(PaguAnggaranHistory::class, 'pagu_anggaran_id');
    }
}
