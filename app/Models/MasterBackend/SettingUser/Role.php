<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'nama_role'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
