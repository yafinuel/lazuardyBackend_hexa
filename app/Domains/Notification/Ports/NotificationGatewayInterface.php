<?php

namespace App\Domains\Notification\Ports;

interface NotificationGatewayInterface
{
    public function sendPushToMany(array $tokens, string $title, string $body, array $data = []): bool;
}
