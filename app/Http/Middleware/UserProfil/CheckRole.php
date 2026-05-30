<?php

namespace App\Http\Middleware\UserProfil;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if (!$user->roles->pluck('nama_role')->intersect($roles)->count()) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
