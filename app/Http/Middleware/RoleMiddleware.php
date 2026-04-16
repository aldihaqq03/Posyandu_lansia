<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        $user = auth()->user();
        if (!$user->is_active) {
            abort(403, 'Profil belum aktif. Silakan lengkapi data diri.');
        }

        $userRole = strtolower($user->jabatan ?? '');
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini.');
        }

        return $next($request);
    }
}
