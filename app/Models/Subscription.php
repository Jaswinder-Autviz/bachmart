<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'subscription_plan_id', 'transaction_id',
        'amount_paid', 'status', 'starts_at', 'expires_at', 'meta',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isFuture();
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->expires_at || $this->expires_at->isPast()) return 0;
        return (int) now()->diffInDays($this->expires_at);
    }
}
