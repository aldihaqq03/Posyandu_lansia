<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check() || !in_array(Auth::user()->jabatan, $roles)) {
        abort(403, 'Unauthorized access.');
    }

    return $next($request);
}
}