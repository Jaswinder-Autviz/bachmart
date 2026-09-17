<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private ShopService $shopService) {}

    public function create()
    {
        if (auth()->user()->shop) {
            return redirect()->route('seller.shop.edit');
        }
        return view('seller.shop.create');
    }

    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_file'] = $request->file('logo');
        }
        unset($data['logo']);

        if ($request->hasFile('cover_image')) {
            $data['cover_file'] = $request->file('cover_image');
        }
        unset($data['cover_image']);

        // Parse opening hours
        if ($request->has('opening_hours')) {
            $data['opening_hours'] = $this->parseOpeningHours($request);
        }

        $this->shopService->create(auth()->user(), $data);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Shop created successfully! You can now add products.');
    }

    public function edit()
    {
        $shop = auth()->user()->shop;
        if (!$shop) return redirect()->route('seller.shop.create');
        return view('seller.shop.edit', compact('shop'));
    }

    public function update(StoreShopRequest $request)
    {
        $shop = auth()->user()->shop;
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_file'] = $request->file('logo');
        }
        unset($data['logo']);

        if ($request->hasFile('cover_image')) {
            $data['cover_file'] = $request->file('cover_image');
        }
        unset($data['cover_image']);

        if ($request->has('opening_hours')) {
            $data['opening_hours'] = $this->parseOpeningHours($request);
        }

        $this->shopService->update($shop, $data);

        return back()->with('success', 'Shop profile updated successfully.');
    }

    private function parseOpeningHours(Request $request): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $hours = [];
        foreach ($days as $day) {
            $hours[$day] = [
                'open' => $request->boolean("hours_{$day}_open"),
                'from' => $request->input("hours_{$day}_from", '09:00'),
                'to' => $request->input("hours_{$day}_to", '21:00'),
            ];
        }
        return $hours;
    }
}
