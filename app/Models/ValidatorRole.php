<?php

namespace App\Models;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidatorRole extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['kode_validator', 'nama_validator', 'is_active'];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_validator_roles',
            'validator_role_id',
            'user_id',
        );
    }
}
