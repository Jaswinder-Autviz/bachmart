<div class="shop-card h-100 d-flex flex-column">
    <div class="position-relative" style="height:120px;overflow:hidden;background:#E2E8F0">
        <img src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" class="w-100 h-100"
             style="object-fit:cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&fit=crop&q=80';">
        <div class="position-absolute" style="bottom:-24px;left:16px">
            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" width="56" height="56"
                 class="rounded-3 border border-3 border-white shadow-sm"
                 style="object-fit:cover;background:#fff"
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=FF5722&color=fff&size=56';">
        </div>
        @if($shop->is_featured)
            <span class="position-absolute top-0 end-0 m-2 badge" style="background:#FF5722;font-size:.7rem;font-weight:700">⭐ Featured</span>
        @endif
    </div>
    <div class="p-3 pt-4 mt-2 d-flex flex-column flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="fw-700 mb-0" style="color:var(--bm-dark);font-size:.95rem">
                    <a href="{{ route('shop.show', $shop->slug) }}" class="text-decoration-none text-dark">
                        {{ $shop->name }}
                    </a>
                </h6>
                <div class="text-muted small mt-1">
                    <i class="bi bi-geo-alt me-1 text-primary-bm"></i>{{ $shop->city }}{{ $shop->area ? ', '.$shop->area : '' }}
                </div>
            </div>
            @if($shop->rating > 0)
            <div class="text-end">
                <div class="text-warning small" style="letter-spacing:-1px">
                    {{ str_repeat('★', round($shop->rating)) }}{{ str_repeat('☆', 5 - round($shop->rating)) }}
                </div>
                <div class="text-muted" style="font-size:.7rem;font-weight:600">{{ $shop->rating }}/5</div>
            </div>
            @endif
        </div>

        @if($shop->is_verified)
            <div class="mb-3">
                <span class="badge" style="background:#ECFDF5;color:#065F46;font-size:.72rem">
                    <i class="bi bi-patch-check-fill me-1 text-success"></i>Verified Shop
                </span>
            </div>
        @endif

        <div class="d-flex gap-2 mt-auto">
            <a href="{{ route('shop.show', $shop->slug) }}" class="btn btn-sm btn-primary-bm flex-grow-1" style="border-radius:8px">
                View Deals
            </a>
            @if($shop->phone)
            <a href="tel:{{ $shop->phone }}"
               onclick="trackLead('call', {{ $shop->id }})"
               class="btn btn-sm btn-call" style="border-radius:8px;padding:0.4rem 0.65rem"
               title="Call Shop">
                <i class="bi bi-telephone-fill"></i>
            </a>
            @endif
            @if($shop->whatsapp)
            <a href="{{ $shop->whatsapp_url }}" target="_blank"
               onclick="trackLead('whatsapp', {{ $shop->id }})"
               class="btn btn-sm btn-whatsapp" style="border-radius:8px;padding:0.4rem 0.65rem"
               title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
            </a>
            @endif
        </div>
    </div>
</div>
