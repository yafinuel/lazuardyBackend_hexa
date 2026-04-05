<?php

namespace App\Domains\Notification\Actions;

use App\Domains\Notification\Ports\NotificationGatewayInterface;

class SendPushNotificationAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected NotificationGatewayInterface $gateway) {}

    public function execute(string $token, string $title, string $body, array $data = []): bool
    {
        return $this->gateway->sendPush($token, $title, $body, $data);
    }
}