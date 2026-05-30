<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = "string";
    protected $fillable = ["nama_jabatan", "level_jabatan", "parent_id", "unit_id"];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(Position::class, "parent_id");
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
