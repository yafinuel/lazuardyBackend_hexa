<?php

namespace App\Domains\Notification\Ports;

interface NotificationGatewayInterface
{
    public function sendPush(string $token, string $title, string $body, array $data = []): bool;
}
