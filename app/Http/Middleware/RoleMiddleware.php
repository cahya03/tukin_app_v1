<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← penting
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
