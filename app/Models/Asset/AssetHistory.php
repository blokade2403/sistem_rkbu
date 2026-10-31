<?php

namespace App\Models\Asset;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasUuids;

    protected $table = 'asset_histories';

    protected $fillable = [
        'asset_id', 'asset_unit_id', 'asset_name', 'asset_register', 'action', 'description',
        'before_data', 'after_data', 'user_id',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getChangesAttribute(): array
    {
        $labels = [
            'nama_asset' => 'Nama aset',
            'no_register' => 'Nomor register',
            'kondisi_asset' => 'Kondisi',
            'status_asset' => 'Status aset',
            'status_reklas_arb' => 'Status usulan penghapusan',
            'lokasi_asset' => 'Lokasi',
            'id_penempatan' => 'Penempatan',
            'pengguna_asset' => 'Pengguna',
            'penanggung_jawab' => 'Penanggung jawab',
            'harga_asset' => 'Harga perolehan',
            'jumlah_asset' => 'Jumlah',
            'serial_number' => 'Nomor seri',
        ];
        $before = $this->before_data ?? [];
        $after = $this->after_data ?? [];
        $changes = [];

        foreach ($labels as $field => $label) {
            $oldValue = $before[$field] ?? null;
            $newValue = $after[$field] ?? null;
            if ((string) $oldValue === (string) $newValue) continue;
            $changes[] = [$label, filled($oldValue) ? (string) $oldValue : '-', filled($newValue) ? (string) $newValue : '-'];
        }

        return $changes;
    }
}
