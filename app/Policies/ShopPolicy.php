<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Shop $shop): bool
    {
        if ($shop->status === 'active') return true;
        if (!$user) return false;
        return $user->isAdmin() || $shop->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() && $user->isActive() && !$user->shop;
    }

    public function update(User $user, Shop $shop): bool
    {
        return $user->isAdmin() || $user->id === $shop->user_id;
    }

    public function delete(User $user, Shop $shop): bool
    {
        return $user->isAdmin();
    }
}
