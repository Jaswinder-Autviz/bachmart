<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'logo', 'cover_image',
        'phone', 'whatsapp', 'email', 'address', 'area', 'city', 'state',
        'pincode', 'latitude', 'longitude', 'opening_hours',
        'facebook', 'instagram', 'twitter', 'website',
        'status', 'is_verified', 'is_featured', 'rating', 'reviews_count', 'views_count',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function approvedProducts()
    {
        return $this->hasMany(Product::class)->where('status', 'approved');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                return $this->logo;
            }
            if (file_exists(public_path('storage/' . $this->logo))) {
                return asset('storage/' . $this->logo);
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF5722&color=fff&size=128&bold=true';
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            if (file_exists(public_path('storage/' . $this->cover_image))) {
                return asset('storage/' . $this->cover_image);
            }
        }
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&auto=format&fit=crop&q=80';
    }

    public function getDirectionUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
        }
        if ($this->address) {
            return "https://www.google.com/maps/search/" . urlencode($this->address . ' ' . $this->city);
        }
        return null;
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if ($this->whatsapp) {
            $number = preg_replace('/[^0-9]/', '', $this->whatsapp);
            return "https://wa.me/91{$number}";
        }
        return null;
    }

    public function isOpenNow(): bool
    {
        if (!$this->opening_hours) return true;
        $day = strtolower(now()->format('l')); // monday, tuesday...
        $hours = $this->opening_hours[$day] ?? null;
        if (!$hours || !isset($hours['open']) || !$hours['open']) return false;
        $now = now()->format('H:i');
        return $now >= ($hours['from'] ?? '00:00') && $now <= ($hours['to'] ?? '23:59');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->address, $this->area, $this->city, $this->state, $this->pincode])
            ->filter()
            ->implode(', ');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
