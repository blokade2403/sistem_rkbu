<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasPerencanaan extends Model
{
    protected $table = 'tugas_perencanaan';

    protected $fillable = [
        'tanggal_informasi', 'informasi_tugas', 'tugas_dari', 'dateline_tugas',
        'tanggal_selesai', 'link_tugas', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'tanggal_informasi' => 'date',
        'dateline_tugas' => 'datetime',
        'tanggal_selesai' => 'date',
    ];
}
