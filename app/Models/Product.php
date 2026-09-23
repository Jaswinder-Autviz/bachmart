<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_id', 'category_id', 'name', 'slug', 'description',
        'brand', 'sku', 'condition', 'original_price', 'offer_price',
        'discount_percent', 'quantity', 'unit', 'status', 'edit_count', 'rejection_reason',
        'is_featured', 'featured_until', 'is_negotiable', 'expires_at',
        'views_count', 'calls_count', 'whatsapp_count', 'directions_count',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'edit_count' => 'integer',
        'is_featured' => 'boolean',
        'is_negotiable' => 'boolean',
        'featured_until' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->original_price > 0 && $product->offer_price > 0) {
                $product->discount_percent = round(
                    (($product->original_price - $product->offer_price) / $product->original_price) * 100,
                    2
                );
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->orderBy('sort_order');
    }

    public function listingPayment()
    {
        return $this->hasOne(Payment::class)->where('type', 'product_listing')->latest();
    }

    public function canSellerEdit(): bool
    {
        return $this->edit_count < 2;
    }

    public function getRemainingEditsAttribute(): int
    {
        return max(0, 2 - (int) $this->edit_count);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function featuredRecord()
    {
        return $this->hasOne(FeaturedProduct::class)->where('status', 'active')->latest();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where(function ($q) {
                $q->whereNull('featured_until')->orWhere('featured_until', '>', now());
            });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%");
        });
    }

    public function scopeInCity($query, string $city)
    {
        return $query->whereHas('shop', fn($q) => $q->where('city', 'like', "%{$city}%"));
    }

    // ─── Accessors ───────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $img = $this->primaryImage ?? $this->images()->first();

        if ($img) {
            if (str_starts_with($img->image_path, 'http://') || str_starts_with($img->image_path, 'https://')) {
                return $img->image_path;
            }
            if (file_exists(public_path('storage/' . $img->image_path))) {
                return asset('storage/' . $img->image_path);
            }
        }

        // Category-based high-quality curated stock photos for demo/seed data
        $catName = strtolower($this->category->name ?? '');
        $photos = [
            'shoes' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
            'fashion' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&auto=format&fit=crop&q=80',
            'electronics' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
            'mobile accessories' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&auto=format&fit=crop&q=80',
            'home & decor' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80',
            'furniture' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&auto=format&fit=crop&q=80',
            'beauty' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&auto=format&fit=crop&q=80',
            'sports' => 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=600&auto=format&fit=crop&q=80',
            'bags' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&auto=format&fit=crop&q=80',
            'watches' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&auto=format&fit=crop&q=80',
            'grocery' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&auto=format&fit=crop&q=80',
            'toys' => 'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=600&auto=format&fit=crop&q=80',
            'hardware' => 'https://images.unsplash.com/photo-1581783342308-f792dbdd27c5?w=600&auto=format&fit=crop&q=80',
        ];

        foreach ($photos as $key => $url) {
            if (str_contains($catName, $key)) {
                return $url;
            }
        }

        return asset('images/product-placeholder.svg');
    }

    public function getSavingsAttribute(): float
    {
        return $this->original_price - $this->offer_price;
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return '₹' . number_format($this->original_price, 2);
    }

    public function getFormattedOfferPriceAttribute(): string
    {
        return '₹' . number_format($this->offer_price, 2);
    }

    public function getDiscountLabelAttribute(): string
    {
        return round($this->discount_percent) . '% OFF';
    }

    public function isActiveAndApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isFeaturedActive(): bool
    {
        return $this->is_featured && ($this->featured_until === null || $this->featured_until->isFuture());
    }
}
