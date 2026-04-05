<?php

namespace App\Domains\Notification\Notifications;

use App\Domains\Notification\Infrastructure\External\Firebase\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPaidExample extends Notification
{
    use Queueable;

    public function __construct(protected array $details) {}

    public function via($notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->details['title'] ?? 'Order Paid',
            'body' => $this->details['body'] ?? 'Your order has been paid successfully.',
            'data' => $this->details['data'] ?? [],
        ];
    }
    
    public function toFcm($notifiable): array
    {
        return [
            'title' => 'Pembayaran Berhasil',
            'body' => 'Klik untuk lihat detail paket kamu.',
            'data' => [
                'type' => 'payment_success',
                'id' => (string) ($this->details['order_id'] ?? '')
            ]
        ];
    }
}
