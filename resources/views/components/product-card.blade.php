@php
    $imgUrl = $product->primary_image_url;
    $isFeatured = $product->isFeaturedActive();
    $discountPercent = $product->discount_percent > 0 
        ? round($product->discount_percent) 
        : ($product->original_price > $product->offer_price 
            ? round((($product->original_price - $product->offer_price) / $product->original_price) * 100) 
            : 0);
@endphp

<div class="product-card" style="border: 1px solid #E2E8F0; border-radius: 6px; box-shadow: none; background: #ffffff;">
    <div class="product-card-img-wrapper" style="border-radius: 5px 5px 0 0; overflow: hidden; position: relative;">
        <a href="{{ route('product.show', $product->slug) }}" class="product-card-img-link" aria-label="{{ $product->name }}">
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                 loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';"
                 style="transform: none !important; transition: none !important;">
        </a>

        @if($discountPercent > 0)
            <span class="product-card-badge product-card-badge--discount" style="border-radius: 4px; box-shadow: none;">
                -{{ $discountPercent }}%
            </span>
        @endif

        @if($isFeatured)
            <span class="product-card-badge product-card-badge--featured" style="border-radius: 4px; box-shadow: none;">
                Featured
            </span>
        @endif
    </div>

    <div class="product-card-body">
        <div class="product-card-meta">
            <span>{{ $product->category->name ?? 'Deal' }}</span>
            @if($product->shop)
                <span class="product-card-meta-dot">·</span>
                <span class="product-card-shop-name">{{ $product->shop->name }}</span>
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
            <a href="{{ route('product.show', $product->slug) }}" class="btn-card-primary" style="border-radius: 4px; box-shadow: none; transition: none;">
                View Details
            </a>
            @if($product->shop && $product->shop->whatsapp)
                <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
                   onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
                   class="btn-card-wa-icon"
                   title="Inquire on WhatsApp"
                   aria-label="WhatsApp"
                   style="border-radius: 4px; box-shadow: none; transition: none;">
                    <i class="bi bi-whatsapp"></i>
                </a>
            @endif
        </div>
    </div>
</div>
