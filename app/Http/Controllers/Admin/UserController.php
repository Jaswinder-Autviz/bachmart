<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function sellers(Request $request)
    {
        $query = User::where('role', 'seller')->with(['shop', 'activeSubscription.subscriptionPlan']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(User $user)
    {
        $user->load(['shop.products', 'subscriptions.subscriptionPlan', 'payments']);

        $stats = [];
        if ($user->shop) {
            $stats = [
                'total_products' => $user->shop->products()->count(),
                'approved_products' => $user->shop->approvedProducts()->count(),
                'total_leads' => \App\Models\Lead::forShop($user->shop->id)->count(),
                'total_revenue' => $user->payments()->where('status', 'completed')->sum('amount'),
            ];
        }

        $activityLogs = ActivityLog::where('user_id', $user->id)->latest()->take(20)->get();

        return view('admin.sellers.show', compact('user', 'stats', 'activityLogs'));
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'active']);
        ActivityLog::log('seller_approved', "Seller {$user->name} approved.", $user);
        return back()->with('success', 'Seller approved.');
    }

    public function block(User $user)
    {
        $user->update(['status' => 'blocked']);
        ActivityLog::log('seller_blocked', "Seller {$user->name} blocked.", $user);
        return back()->with('success', 'Seller blocked.');
    }

    public function unblock(User $user)
    {
        $user->update(['status' => 'active']);
        ActivityLog::log('seller_unblocked', "Seller {$user->name} unblocked.", $user);
        return back()->with('success', 'Seller unblocked.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin accounts.');
        }
        ActivityLog::log('seller_deleted', "Seller {$user->name} deleted.", null, ['email' => $user->email]);
        $user->delete();
        return redirect()->route('admin.sellers.index')->with('success', 'Seller account deleted.');
    }

    public function customers(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }
}
