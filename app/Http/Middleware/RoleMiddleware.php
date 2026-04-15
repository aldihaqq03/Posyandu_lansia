<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
<<<<<<< HEAD
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check() || !in_array(Auth::user()->jabatan, $roles)) {
        abort(403, 'Unauthorized access.');
    }

    return $next($request);
}
}
=======
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

        $userRole = strtolower(auth()->user()->jabatan);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
>>>>>>> aldi
