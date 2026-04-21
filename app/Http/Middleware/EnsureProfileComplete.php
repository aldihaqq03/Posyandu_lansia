<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not logged in, we let auth middleware handle it or let it pass
        if (! $request->user()) {
            return $next($request);
        }

        $isActive = $request->user()->is_active;
        
        // Define if current route is lengkapi page or update form
        $isLengkapiRoute = $request->routeIs('profile.lengkapi') || $request->routeIs('profile.lengkapi.update') || $request->routeIs('logout');

        // If inactive and accessing other routes, block and redirect to lengkapi
        if (! $isActive && ! $isLengkapiRoute) {
            return redirect()->route('profile.lengkapi');
        }

        // If already active and trying to access lengkapi, redirect to dashboard
        if ($isActive && ($request->routeIs('profile.lengkapi') || $request->routeIs('profile.lengkapi.update'))) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}