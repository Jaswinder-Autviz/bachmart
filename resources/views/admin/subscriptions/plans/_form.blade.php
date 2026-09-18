@php
    $plan = $subscriptionPlan ?? null;
    $featuresText = $plan && is_array($plan->features) ? implode("\n", $plan->features) : old('features', '');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-600 small">Plan Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm"
               value="{{ old('name', $plan->name ?? '') }}" placeholder="e.g. Starter, Pro, Enterprise" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600 small">Price (₹) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="price" class="form-control form-control-sm"
               value="{{ old('price', $plan->price ?? '') }}" placeholder="0.00" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600 small">Max Products Allowed <span class="text-danger">*</span></label>
        <input type="number" name="max_products" class="form-control form-control-sm"
               value="{{ old('max_products', $plan->max_products ?? 10) }}" required>
        <div class="form-text small">Enter -1 for unlimited products.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600 small">Billing Cycle <span class="text-danger">*</span></label>
        <select name="billing_cycle" class="form-select form-select-sm" required>
            <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label fw-600 small">Short Description</label>
        <textarea name="description" class="form-control form-control-sm" rows="2"
                  placeholder="A brief summary of who this plan is for">{{ old('description', $plan->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label fw-600 small">Features List (one feature per line)</label>
        <textarea name="features" class="form-control form-control-sm font-monospace" rows="4"
                  placeholder="Up to 25 live products&#10;Verified Shop Badge&#10;WhatsApp Lead Tracking&#10;Direct Call Support">{{ $featuresText }}</textarea>
    </div>

    <div class="col-12">
        <hr class="my-2">
        <label class="form-label fw-700 small text-dark d-block">Premium Perks & Visibility</label>
        <div class="row g-2">
            <div class="col-sm-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="featured_placement" id="featPlacement" value="1"
                           {{ old('featured_placement', $plan->featured_placement ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="featPlacement">Featured Placement on Homepage</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="advanced_analytics" id="advAnalytics" value="1"
                           {{ old('advanced_analytics', $plan->advanced_analytics ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="advAnalytics">Advanced Analytics & Heatmaps</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="priority_support" id="prioSupport" value="1"
                           {{ old('priority_support', $plan->priority_support ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="prioSupport">Priority Support</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="premium_profile" id="premProfile" value="1"
                           {{ old('premium_profile', $plan->premium_profile ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="premProfile">Verified Badge & Premium Profile</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <hr class="my-2">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label fw-600 small">Display Order</label>
                <input type="number" name="sort_order" class="form-control form-control-sm"
                       value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
            </div>
            <div class="col-sm-4 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_popular" id="isPopular" value="1"
                           {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="isPopular">Highlight as "Popular"</label>
                </div>
            </div>
            <div class="col-sm-4 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                           {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="isActive">Active (Visible to Sellers)</label>
                </div>
            </div>
        </div>
    </div>
</div>
