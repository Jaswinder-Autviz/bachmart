<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = auth()->user();

        if ($user->isBlocked()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been blocked. Please contact support.');
        }

        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
