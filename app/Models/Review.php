<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id', 'user_id', 'product_id', 'rating', 'comment',
        'is_approved', 'is_featured', 'admin_note',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
