<?php

namespace App\Http\Middleware\UserProfil;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFase
{
    public function handle(Request $request, Closure $next)
    {
        $tahun = TahunAnggaran::where('status', 'aktif')->first();

        if (!$tahun) {
            abort(403, 'Tahun anggaran tidak aktif');
        }

        if ($tahun->fase->nama_fase !== 'Perencanaan') {
            abort(403, 'Fase saat ini tidak mengizinkan edit');
        }

        return $next($request);
    }
}
