<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with('user')->withCount(['products', 'approvedProducts']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $shops = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.shops.index', compact('shops'));
    }

    public function show(Shop $shop)
    {
        $shop->load(['user', 'products.primaryImage', 'reviews.user']);
        return view('admin.shops.show', compact('shop'));
    }

    public function activate(Shop $shop)
    {
        $shop->update(['status' => 'active']);
        ActivityLog::log('shop_activated', "Shop {$shop->name} activated.", $shop);
        return back()->with('success', 'Shop activated.');
    }

    public function deactivate(Shop $shop)
    {
        $shop->update(['status' => 'inactive']);
        ActivityLog::log('shop_deactivated', "Shop {$shop->name} deactivated.", $shop);
        return back()->with('success', 'Shop deactivated.');
    }

    public function verify(Shop $shop)
    {
        $shop->update(['is_verified' => true]);
        return back()->with('success', 'Shop verified.');
    }

    public function feature(Shop $shop)
    {
        $shop->update(['is_featured' => !$shop->is_featured]);
        $msg = $shop->is_featured ? 'Shop featured.' : 'Shop unfeatured.';
        return back()->with('success', $msg);
    }
}
