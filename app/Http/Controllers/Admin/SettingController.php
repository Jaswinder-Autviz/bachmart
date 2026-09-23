<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name', 'site_tagline', 'site_phone', 'site_email',
            'currency', 'currency_symbol',
            'featured_price_3days', 'featured_price_7days', 'featured_price_15days',
            'product_listing_price', 'max_free_products',
            'contact_address', 'contact_city',
            'facebook_url', 'instagram_url', 'twitter_url',
            'meta_title', 'meta_description', 'meta_keywords',
            'razorpay_enabled', 'razorpay_key_id',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Handle logo upload separately if needed
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        Cache::flush(); // Clear all settings cache

        return back()->with('success', 'Settings saved successfully.');
    }
}
