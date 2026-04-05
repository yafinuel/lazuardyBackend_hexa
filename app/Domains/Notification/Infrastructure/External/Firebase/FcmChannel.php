<?php

namespace App\Domains\Notification\Infrastructure\External\Firebase;

use App\Domains\Notification\Ports\NotificationGatewayInterface;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected NotificationGatewayInterface $gateway) {}
    
    /**
     * @param mixed $notifiable
     * @param \App\Domains\Notification\Notifications\OrderPaidExample $notification
     */
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toFcm($notifiable);
        $token = $notifiable->fcm_token; // Asumsi kolom fcm_token ada di tabel users

        if ($token) {
            $this->gateway->sendPush(
                $token,
                $message['title'],
                $message['body'],
                $message['data'] ?? []
            );
        }
    }
}
