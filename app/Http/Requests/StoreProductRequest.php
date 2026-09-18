<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSeller();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:10', 'max:3000'],
            'brand' => ['nullable', 'string', 'max:100'],
            'sku' => ['nullable', 'string', 'max:100'],
            'condition' => ['required', 'in:new,like_new,good,fair'],
            'original_price' => ['required', 'numeric', 'min:1', 'max:9999999'],
            'offer_price' => ['required', 'numeric', 'min:1', 'max:9999999', 'lte:original_price'],
            'quantity' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_negotiable' => ['sometimes', 'boolean'],
            'expires_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'offer_price.lte' => 'Clearance price must be less than or equal to original price.',
            'images.*.max' => 'Each image must be less than 5 MB.',
            'images.*.mimes' => 'Only JPEG, PNG, JPG and WebP images are allowed.',
        ];
    }
}
