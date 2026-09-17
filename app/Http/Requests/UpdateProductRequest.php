<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');
        return auth()->check() && 
               (auth()->user()->isAdmin() || $product->user_id === auth()->id());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'brand' => ['nullable', 'string', 'max:100'],
            'sku' => ['nullable', 'string', 'max:100'],
            'condition' => ['required', 'in:new,like_new,good,fair'],
            'original_price' => ['required', 'numeric', 'min:1', 'max:9999999'],
            'offer_price' => ['required', 'numeric', 'min:1', 'max:9999999', 'lt:original_price'],
            'quantity' => ['required', 'integer', 'min:0'],
            'images' => ['sometimes', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_negotiable' => ['sometimes', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'offer_price.lt' => 'Offer price must be less than original price.',
        ];
    }
}
