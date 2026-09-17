<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopService
{
    public function create(User $user, array $data): Shop
    {
        $data['user_id'] = $user->id;
        $data['slug'] = $this->generateSlug($data['name']);
        $data['status'] = 'active'; // auto-activate on creation

        if (!empty($data['logo_file'])) {
            $data['logo'] = $data['logo_file']->store('shops/logos', 'public');
            unset($data['logo_file']);
        }

        if (!empty($data['cover_file'])) {
            $data['cover_image'] = $data['cover_file']->store('shops/covers', 'public');
            unset($data['cover_file']);
        }

        return Shop::create($data);
    }

    public function update(Shop $shop, array $data): Shop
    {
        if (!empty($data['logo_file'])) {
            if ($shop->logo) Storage::disk('public')->delete($shop->logo);
            $data['logo'] = $data['logo_file']->store('shops/logos', 'public');
            unset($data['logo_file']);
        }

        if (!empty($data['cover_file'])) {
            if ($shop->cover_image) Storage::disk('public')->delete($shop->cover_image);
            $data['cover_image'] = $data['cover_file']->store('shops/covers', 'public');
            unset($data['cover_file']);
        }

        $shop->update($data);
        return $shop->fresh();
    }

    public function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Shop::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function updateRating(Shop $shop): void
    {
        $avg = $shop->approvedReviews()->avg('rating');
        $count = $shop->approvedReviews()->count();
        $shop->update(['rating' => round($avg ?? 0, 2), 'reviews_count' => $count]);
    }
}
