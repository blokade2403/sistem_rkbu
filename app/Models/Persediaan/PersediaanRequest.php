<?php

namespace App\Models\Persediaan;

use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PersediaanRequest extends Model
{
    use HasUuids;

    protected $table = 'persediaan_requests';

    protected $fillable = [
        'persediaan_item_id', 'unit_peminta_id', 'requested_by', 'jumlah', 'keperluan',
        'status', 'reviewed_by', 'reviewed_at', 'catatan_verifikasi',
    ];

    protected $casts = ['jumlah' => 'decimal:3', 'reviewed_at' => 'datetime'];

    public function item()
    {
        return $this->belongsTo(PersediaanItem::class, 'persediaan_item_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_peminta_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
