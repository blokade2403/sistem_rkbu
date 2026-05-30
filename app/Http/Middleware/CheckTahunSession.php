<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTahunSession
{
    public function handle($request, Closure $next)
    {
        if (!session()->has("tahun_anggaran")) {
            return redirect()->route("login");
        }

        return $next($request);
    }
}
