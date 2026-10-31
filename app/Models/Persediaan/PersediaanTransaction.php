<?php

namespace App\Models\Persediaan;

use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\UsulanBelanja\SpjDetail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PersediaanTransaction extends Model
{
    use HasUuids;

    protected $table = 'persediaan_transactions';

    protected $fillable = [
        'persediaan_item_id', 'unit_id', 'unit_peminta_id', 'spj_detail_id', 'persediaan_request_id', 'jenis',
        'jumlah', 'stok_sebelum', 'stok_sesudah', 'tanggal', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'jumlah' => 'decimal:3', 'stok_sebelum' => 'decimal:3',
        'stok_sesudah' => 'decimal:3', 'tanggal' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(PersediaanItem::class, 'persediaan_item_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function requestingUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_peminta_id');
    }

    public function spjDetail()
    {
        return $this->belongsTo(SpjDetail::class, 'spj_detail_id');
    }

    public function request()
    {
        return $this->belongsTo(PersediaanRequest::class, 'persediaan_request_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
