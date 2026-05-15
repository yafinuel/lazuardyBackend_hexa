<?php

namespace App\Domains\Notification\Infrastructure\External\Firebase;

use App\Domains\Notification\Ports\NotificationGatewayInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected NotificationGatewayInterface $gateway) {}
    
    /**
     * @param mixed $notifiable
     * @param \App\Domains\Notification\Notifications\TemplateNotification $notification
     */
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toFcm($notifiable);
        $tokens = [];

        if (method_exists($notifiable, 'fcmTokens')) {
            $tokens = $notifiable->fcmTokens()->pluck('fcm_token')->filter()->all();
        }

        $tokens = array_values(array_unique($tokens));

        if ($tokens === []) {
            Log::info('FCM Skipped: missing fcm_token', [
                'notifiable_id' => $notifiable->id ?? null,
                'notification' => get_class($notification),
            ]);
            return;
        }

        Log::info('FCM Send Attempt', [
            'notifiable_id' => $notifiable->id ?? null,
            'notification' => get_class($notification),
            'token_count' => count($tokens),
        ]);

        foreach ($tokens as $token) {
            $this->gateway->sendPush(
                $token,
                $message['title'],
                $message['body'],
                $message['data'] ?? []
            );
        }
    }
}
