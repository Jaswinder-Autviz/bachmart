<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductApproved extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "Your product '{$this->product->name}' has been approved and is now live!",
            'product_id' => $this->product->id,
            'product_slug' => $this->product->slug,
            'type' => 'product_approved',
        ];
    }
}
