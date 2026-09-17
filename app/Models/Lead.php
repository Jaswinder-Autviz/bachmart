<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'shop_id', 'customer_id', 'type',
        'ip_address', 'user_agent', 'city', 'source',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForShop($query, int $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
