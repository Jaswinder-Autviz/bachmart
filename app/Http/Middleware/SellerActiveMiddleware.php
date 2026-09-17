<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerActiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isSeller()) {
            // Check if seller has a shop created
            if (!$user->shop && !$request->routeIs('seller.shop.create') && !$request->routeIs('seller.shop.store')) {
                return redirect()->route('seller.shop.create')
                    ->with('info', 'Please create your shop profile first.');
            }
        }

        return $next($request);
    }
}
