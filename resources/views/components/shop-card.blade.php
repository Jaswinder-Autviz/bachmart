<div class="shop-card h-100 d-flex flex-column">
    <div class="position-relative" style="height: 130px; overflow: hidden; background: #0F172A;">
        <img src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" class="w-100 h-100"
             style="object-fit: cover; opacity: 0.9;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&fit=crop&q=80';">
        <div class="position-absolute" style="bottom: -24px; left: 18px; z-index: 2;">
            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" width="60" height="60"
                 class="rounded-4 border border-3 border-white shadow-sm"
                 style="object-fit: cover; background: #FFFFFF;"
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=FF5722&color=fff&size=60';">
        </div>
        @if($shop->is_featured)
            <span class="position-absolute top-0 end-0 m-3 badge rounded-pill" style="background: var(--bm-primary-gradient); font-size: 0.72rem; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                <i class="bi bi-star-fill me-1" style="font-size: 0.65rem;"></i>Featured Store
            </span>
        @endif
    </div>

    <div class="p-3 pt-4 mt-2 d-flex flex-column flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="fw-800 mb-1" style="color: var(--bm-dark); font-size: 1rem;">
                    <a href="{{ route('shop.show', $shop->slug) }}" class="text-decoration-none text-dark">
                        {{ $shop->name }}
                    </a>
                </h6>
                <div class="text-muted small">
                    <i class="bi bi-geo-alt-fill me-1 text-primary-bm"></i>{{ $shop->city }}{{ $shop->area ? ', '.$shop->area : '' }}
                </div>
            </div>
            @if($shop->rating > 0)
            <div class="text-end">
                <div class="text-warning small" style="letter-spacing: -1px">
                    {{ str_repeat('★', round($shop->rating)) }}{{ str_repeat('☆', 5 - round($shop->rating)) }}
                </div>
                <div class="text-muted small fw-700">{{ number_format($shop->rating, 1) }} / 5</div>
            </div>
            @endif
        </div>

        @if($shop->is_verified)
            <div class="mb-3">
                <span class="badge rounded-pill" style="background: #ECFDF5; color: #065F46; font-size: 0.72rem; font-weight: 700; border: 1px solid #A7F3D0;">
                    <i class="bi bi-patch-check-fill me-1 text-success"></i>Verified Merchant
                </span>
            </div>
        @endif

        <div class="d-flex gap-2 mt-auto pt-2">
            <a href="{{ route('shop.show', $shop->slug) }}" class="btn btn-sm btn-primary-bm flex-grow-1">
                View Deals
            </a>
            @if($shop->phone)
            <a href="tel:{{ $shop->phone }}"
               onclick="trackLead('call', {{ $shop->id }})"
               class="btn btn-sm btn-call rounded-pill px-3"
               title="Call Shopkeeper">
                <i class="bi bi-telephone-fill"></i>
            </a>
            @endif
            @if($shop->whatsapp)
            <a href="{{ $shop->whatsapp_url }}" target="_blank"
               onclick="trackLead('whatsapp', {{ $shop->id }})"
               class="btn btn-sm btn-whatsapp rounded-pill px-3"
               title="Chat on WhatsApp">
                <i class="bi bi-whatsapp"></i>
            </a>
            @endif
        </div>
    </div>
</div>
