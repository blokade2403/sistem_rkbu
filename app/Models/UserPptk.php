<?php

namespace App\Models;

use App\Models\MasterBackend\UserProfil\User;
use App\Models\WorkFlow\PptkKategori;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPptk extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'user_pptk';

    protected $fillable = ['user_id', 'pptk_kategori_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pptkKategori()
    {
        return $this->belongsTo(PptkKategori::class, 'pptk_kategori_id');
    }
}
