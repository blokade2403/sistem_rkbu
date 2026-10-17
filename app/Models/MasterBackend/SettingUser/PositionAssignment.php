<?php

namespace App\Models\MasterBackend\SettingUser;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionAssignment extends Model
{
    use HasUuids;

    protected $fillable = [
        'position_id',
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'position_assignment_id'
        );
    }

    public function parent()
    {
        return $this->belongsTo(
            Position::class,
            'parent_id'
        );
    }

    public function children()
    {
        return $this->hasMany(
            Position::class,
            'parent_id'
        );
    }
}
