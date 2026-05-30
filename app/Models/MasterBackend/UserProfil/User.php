<?php

namespace App\Models\MasterBackend\UserProfil;

use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Pptk;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\LevelUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\AdminPendukung;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = 'users';
    protected $fillable = [
        'id',
        'unit_id',
        'position_id',
        'nip',
        'nama',
        'username',
        'email',
        'password',
        'status_user',
        'status_edit',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function username()
    {
        return 'username';
    }

    public $incrementing = false;
    protected $keyType = 'string';

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
