<?php

namespace App\Models\WorkFlow;

use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PptkKategori extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['nama_kategori'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_pptk', 'pptk_kategori_id', 'user_id');
    }
}
