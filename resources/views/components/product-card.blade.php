@php
    $imgUrl = $product->primary_image_url;
    $isFeatured = $product->isFeaturedActive();
    $discountPercent = $product->discount_percent > 0 
        ? round($product->discount_percent) 
        : ($product->original_price > $product->offer_price 
            ? round((($product->original_price - $product->offer_price) / $product->original_price) * 100) 
            : 0);
@endphp

<div class="card-3d-tilt-wrapper">
    <div class="product-card product-card-3d" data-3d-tilt data-tilt-max="9">
        <div class="card-3d-glare"></div>
        <div class="product-card-img-wrapper">
            <a href="{{ route('product.show', $product->slug) }}" class="product-card-img-link" aria-label="{{ $product->name }}">
                <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                     loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.svg') }}';">
            </a>

            @if($discountPercent > 0)
                <span class="product-card-badge product-card-badge--discount">
                    -{{ $discountPercent }}%
                </span>
            @endif

            @if($isFeatured)
                <span class="product-card-badge product-card-badge--featured">
                    Featured
                </span>
            @endif

            {{-- Subtle quick view button on hover --}}
            <div class="product-card-hover-action">
                <a href="{{ route('product.show', $product->slug) }}" class="btn-quick-view">
                    View Deal
                </a>
            </div>
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
                <a href="{{ route('product.show', $product->slug) }}" class="btn-card-primary btn-card-3d-primary">
                    View Details
                </a>
                @if($product->shop && $product->shop->whatsapp)
                    <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
                       onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
                       class="btn-card-wa-icon btn-card-3d-wa"
                       title="Inquire on WhatsApp"
                       aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
