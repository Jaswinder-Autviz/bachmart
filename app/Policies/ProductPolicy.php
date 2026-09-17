<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // Public browse
    }

    public function view(?User $user, Product $product): bool
    {
        // Public can view approved products
        if ($product->status === 'approved') return true;
        // Owner or admin can view any
        if (!$user) return false;
        return $user->isAdmin() || $product->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() && $user->isActive();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || ($user->id === $product->user_id && $user->isActive());
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->id === $product->user_id;
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function feature(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->id === $product->user_id;
    }
}
