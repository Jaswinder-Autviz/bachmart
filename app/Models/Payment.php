<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_id', 'type', 'product_id', 'payable_type', 'payable_id',
        'transaction_id', 'gateway', 'amount', 'currency', 'status',
        'gateway_order_id', 'gateway_payment_id', 'notes', 'gateway_response',
        'metadata', 'paid_at', 'used_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

    // ─── Scopes & Helpers ─────────────────────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->status === 'success';
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null || $this->product_id !== null;
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'success']);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeListing($query)
    {
        return $query->where('type', 'product_listing');
    }

    public function scopeUnusedListing($query)
    {
        return $query->where('type', 'product_listing')
                     ->whereIn('status', ['completed', 'success'])
                     ->whereNull('product_id')
                     ->whereNull('used_at');
    }
}
