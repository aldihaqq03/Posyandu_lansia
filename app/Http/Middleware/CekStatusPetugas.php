<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekStatusPetugas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Jika BELUM aktif dan TIDAK sedang di route lengkapi profil
            if (
                !Auth::user()->is_active &&
                !$request->routeIs('profile.lengkapi') &&
                !$request->routeIs('profile.lengkapi.update')
            ) {
                return redirect()->route('profile.lengkapi');
            }

            // Jika SUDAH aktif tapi coba akses halaman lengkapi profil
            if (Auth::user()->is_active && $request->routeIs('profile.lengkapi')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}