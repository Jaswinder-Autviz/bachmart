<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'billing_cycle',
        'max_products', 'featured_placement', 'priority_support',
        'advanced_analytics', 'premium_profile', 'promotional_placement',
        'featured_product_discount', 'features', 'sort_order', 'is_active', 'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'featured_placement' => 'boolean',
        'priority_support' => 'boolean',
        'advanced_analytics' => 'boolean',
        'premium_profile' => 'boolean',
        'promotional_placement' => 'boolean',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getMaxProductsLabelAttribute(): string
    {
        return $this->max_products === -1 ? 'Unlimited' : (string)$this->max_products;
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
