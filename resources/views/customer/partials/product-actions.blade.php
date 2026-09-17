<div class="d-flex flex-column gap-2">
    @if($product->shop->phone)
    <a href="tel:{{ $product->shop->phone }}"
       onclick="trackLead('call', {{ $product->shop->id }}, {{ $product->id }})"
       class="btn btn-success py-2 fw-600">
        <i class="bi bi-telephone-fill me-2"></i>Call Shop
    </a>
    @endif
    @if($product->shop->whatsapp)
    <a href="{{ $product->shop->whatsapp_url }}" target="_blank"
       onclick="trackLead('whatsapp', {{ $product->shop->id }}, {{ $product->id }})"
       class="btn py-2 fw-600 text-white" style="background:#25d366;border:none">
        <i class="bi bi-whatsapp me-2"></i>WhatsApp Shop
    </a>
    @endif
    @if($product->shop->direction_url)
    <a href="{{ $product->shop->direction_url }}" target="_blank"
       onclick="trackLead('direction', {{ $product->shop->id }}, {{ $product->id }})"
       class="btn btn-outline-secondary py-2 fw-600">
        <i class="bi bi-map me-2"></i>Get Directions
    </a>
    @endif
    <a href="{{ route('shop.show', $product->shop->slug) }}" class="btn btn-outline-secondary py-2">
        <i class="bi bi-shop me-2"></i>Visit Shop Page
    </a>
</div>
<div class="mt-3 p-2 rounded-2 text-center small text-muted" style="background:#f7f8fa">
    <i class="bi bi-info-circle me-1"></i>Purchase directly at the shop — no online payment needed.
</div>
