<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(User $user, array $data, array $images): Product
    {
        return DB::transaction(function () use ($user, $data, $images) {
            $data['user_id'] = $user->id;
            $data['shop_id'] = $user->shop->id;
            $data['slug'] = $this->generateSlug($data['name']);
            $data['status'] = 'pending';

            $product = Product::create($data);

            $this->saveImages($product, $images);

            ActivityLog::log('product_created', "Product '{$product->name}' submitted for approval.", $product);

            return $product;
        });
    }

    public function update(Product $product, array $data, array $newImages = []): Product
    {
        return DB::transaction(function () use ($product, $data, $newImages) {
            // If seller edits an approved product, re-submit for review
            if ($product->status === 'approved' && !auth()->user()->isAdmin()) {
                $data['status'] = 'pending';
            }

            $product->update($data);

            if (!empty($newImages)) {
                $this->saveImages($product, $newImages);
            }

            ActivityLog::log('product_updated', "Product '{$product->name}' updated.", $product);

            return $product->fresh();
        });
    }

    public function approve(Product $product): void
    {
        $product->update(['status' => 'approved', 'rejection_reason' => null]);
        ActivityLog::log('product_approved', "Product '{$product->name}' approved.", $product);

        // Notify seller
        $product->user->notify(new \App\Notifications\ProductApproved($product));
    }

    public function reject(Product $product, string $reason): void
    {
        $product->update(['status' => 'rejected', 'rejection_reason' => $reason]);
        ActivityLog::log('product_rejected', "Product '{$product->name}' rejected.", $product);

        $product->user->notify(new \App\Notifications\ProductRejected($product, $reason));
    }

    public function delete(Product $product): void
    {
        // Delete images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
        }
        $product->delete();
    }

    private function saveImages(Product $product, array $images): void
    {
        $isPrimary = $product->images()->count() === 0;

        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store("products/{$product->id}", 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary && $index === 0,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }
    }

    private function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function incrementStat(Product $product, string $stat): void
    {
        $columns = ['view' => 'views_count', 'call' => 'calls_count', 'whatsapp' => 'whatsapp_count', 'direction' => 'directions_count'];
        if (isset($columns[$stat])) {
            $product->increment($columns[$stat]);
        }
    }
}
