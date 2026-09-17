<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSeller();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'phone' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'area' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'opening_hours' => ['nullable', 'array'],
        ];
    }
}
