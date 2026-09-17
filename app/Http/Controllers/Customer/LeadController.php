<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:call,whatsapp,direction,view'],
            'shop_id' => ['required', 'exists:shops,id'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        Lead::create([
            'type' => $validated['type'],
            'shop_id' => $validated['shop_id'],
            'product_id' => $validated['product_id'] ?? null,
            'customer_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'source' => $request->header('Referer'),
        ]);

        // Update shop and product counters
        if ($validated['type'] !== 'view') {
            Shop::where('id', $validated['shop_id'])->increment(
                match($validated['type']) {
                    'call' => 'views_count', // reuse views for shop stats (leads tracked separately)
                    default => 'views_count',
                }
            );
        }

        if ($validated['product_id']) {
            $col = match($validated['type']) {
                'call' => 'calls_count',
                'whatsapp' => 'whatsapp_count',
                'direction' => 'directions_count',
                'view' => 'views_count',
            };
            Product::where('id', $validated['product_id'])->increment($col);
        }

        return response()->json(['success' => true]);
    }
}
