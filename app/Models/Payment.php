<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'payable_type', 'payable_id', 'transaction_id', 'gateway',
        'amount', 'currency', 'status', 'gateway_order_id', 'gateway_payment_id',
        'notes', 'gateway_response', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
