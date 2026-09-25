@php
    $imgUrl = $product->primary_image_url;
    $isFeatured = $product->isFeaturedActive();
    $discountPercent = $product->discount_percent > 0 
        ? round($product->discount_percent) 
        : ($product->original_price > $product->offer_price 
            ? round((($product->original_price - $product->offer_price) / $product->original_price) * 100) 
            : 0);
@endphp

<div class="product-card">
    <div class="product-card-img-wrapper">
        <a href="{{ route('product.show', $product->slug) }}" class="product-card-img-link" aria-label="{{ $product->name }}">
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                 loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
        </a>

        @if($discountPercent > 0)
            <span class="product-card-badge product-card-badge--discount">
                -{{ $discountPercent }}% OFF
            </span>
        @endif

        @if($isFeatured)
            <span class="product-card-badge product-card-badge--featured">
                <i class="bi bi-star-fill me-1" style="font-size: 0.65rem;"></i>Featured
            </span>
        @endif
    </div>

    <div class="product-card-body">
        <div class="product-card-meta">
            <span>{{ $product->category->name ?? 'Clearance' }}</span>
            @if($product->shop)
                <span class="product-card-meta-dot">·</span>
                <span class="product-card-shop-name" title="{{ $product->shop->name }}">{{ $product->shop->name }}</span>
            @endif
        </div>

        <h6 class="product-card-title">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h6>

        <div class="product-card-pricing">
            <span class="price-offer">₹{{ number_format($product->offer_price) }}</span>
            @if($product->original_price > $product->offer_price)
                <span class="price-original">₹{{ number_format($product->original_price) }}</span>
            @endif
        </div>

        <div class="product-card-footer">
            <a href="{{ route('product.show', $product->slug) }}" class="btn-card-primary">
                View Deal
            </a>
            @if($product->shop && $product->shop->whatsapp)
                <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
                   onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
                   class="btn-card-wa-icon"
                   title="Chat with Shopkeeper"
                   aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            @endif
        </div>
    </div>
</div>
