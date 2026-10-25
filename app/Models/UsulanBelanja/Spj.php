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
        'pengadaan_step',
        'pengadaan_workflow_step_id',
        'idpaket',
        'no_surat_pesanan',
        'tgl_surat_pesanan',
        'foto_barang_datang',
        'foto_barang_datang_2',
        'foto_barang_datang_3',
        'tgl_barang_datang',
        'keterangan_barang_datang',
        'status_verifikasi_pengurus_barang',
        'keterangan_verif_pengurus_barang',
        'no_bast_serah_terima_unit',
        'nama_penerima_barang',
        'validator_pengurus_barang_id',
        'tgl_verif_pengurus_barang',
        'tgl_bast',
        'no_ba',
        'no_ba_hp',
        'no_ba_bp',
        'no_dpa',
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
