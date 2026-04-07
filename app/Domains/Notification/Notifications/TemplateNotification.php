<?php

namespace App\Domains\Notification\Notifications;

use App\Domains\Notification\Infrastructure\External\Firebase\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TemplateNotification extends Notification
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
            'title' => $this->details['title'],
            'body' => $this->details['body'],
            'data' => $this->details['data'] ?? [],
        ];
    }
    
    public function toFcm($notifiable): array
    {
        return [
            'title' => $this->details['title'],
            'body' => $this->details['body'],
            'data' => $this->details['data'] ?? [],
        ];
    }
}
