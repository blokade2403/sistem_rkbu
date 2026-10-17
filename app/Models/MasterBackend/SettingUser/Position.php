<?php

namespace App\Models\MasterBackend\SettingUser;

use App\Models\MasterBackend\UserProfil\User;
use App\Models\WorkFlow\PositionSubKategoriRkbu;
use App\Models\WorkFlow\WorkflowInstance;
use App\Models\WorkFlow\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Position extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['nama_jabatan', 'level_jabatan', 'parent_id', 'unit_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function users()
    {
        return $this->hasMany(
            User::class,
            'position_id'
        );
    }

    public function assignments()
    {
        return $this->hasMany(PositionAssignment::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function subKategoriRkbus()
    {
        return $this->hasMany(PositionSubKategoriRkbu::class, 'position_id', 'id');
    }

    public function workflowSteps()
    {
        return $this->hasMany(
            WorkflowStep::class
        );
    }

    public function workflowInstances()
    {
        return $this->hasMany(
            WorkflowInstance::class,
            'current_position_id'
        );
    }
}
