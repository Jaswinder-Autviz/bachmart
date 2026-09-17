<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductRejected extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product,
        public string $reason
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "Your product '{$this->product->name}' was rejected. Reason: {$this->reason}",
            'product_id' => $this->product->id,
            'reason' => $this->reason,
            'type' => 'product_rejected',
        ];
    }
}
