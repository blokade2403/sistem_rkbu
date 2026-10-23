<?php

namespace App\Models\UsulanBelanja;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsulanBelanja extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'usulan_belanjas';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'rkbu_id',
        'no_usulan_belanja',
        'nomor_urut',
        'user_id',
        'nama_pengusul',
        'unit_id',
        'sub_kategori_rkbu_id',
        'jenis_kategori_rkbu_id',
        'tahun_anggaran_id',
        'status_usulan',
        'tanggal_validasi',
        'validator_user_id',
        'nama_validasi',
        'catatan',
        'workflow_instance_id',
    ];

    protected $casts = [
        'nomor_urut' => 'integer',
        'tanggal_validasi' => 'datetime',
    ];

    public function rkbu(): BelongsTo
    {
        return $this->belongsTo(
            Rkbu::class,
            'rkbu_id',
            'id'
        );
    }

    public function pengusul(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\MasterBackend\UserProfil\User::class,
            'user_id',
            'id'
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\MasterBackend\SettingUser\Unit::class,
            'unit_id',
            'id'
        );
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\MasterBackend\UserProfil\User::class,
            'validator_user_id',
            'id'
        );
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(
            TahunAnggaran::class,
            'tahun_anggaran_id',
            'id'
        );
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Workflow\WorkflowInstance::class,
            'workflow_instance_id',
            'id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(UsulanBelanjaItem::class, 'usulan_belanja_id');
    }

    public function spjs(): HasMany
    {
        return $this->hasMany(Spj::class, 'usulan_belanja_id');
    }
}
