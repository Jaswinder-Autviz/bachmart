<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionActivated extends Notification
{
    use Queueable;

    public function __construct(public Subscription $subscription) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "Your {$this->subscription->subscriptionPlan->name} plan is now active! Expires on " . $this->subscription->expires_at?->format('d M Y'),
            'subscription_id' => $this->subscription->id,
            'plan_name' => $this->subscription->subscriptionPlan->name,
            'type' => 'subscription_activated',
        ];
    }
}
