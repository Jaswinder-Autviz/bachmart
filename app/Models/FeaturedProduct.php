<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'package', 'amount_paid',
        'transaction_id', 'status', 'starts_at', 'expires_at', 'meta',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'meta' => 'array',
        'amount_paid' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isFuture();
    }

    public static function getDaysFromPackage(string $package): int
    {
        return match($package) {
            '3days' => 3,
            '7days' => 7,
            '15days' => 15,
            default => 7,
        };
    }
}
