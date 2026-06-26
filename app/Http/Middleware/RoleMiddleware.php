<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            // Check if it's an AJAX/JSON request
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden - You do not have permission to access this resource.'], 403);
            }
            
            // Redirect back with an error, or to dashboard if they have one
            return redirect()->route('dashboard')->with('error', 'Unauthorized Action. You do not have permission to view that page.');
        }

        return $next($request);
    }
}
