<?php

namespace App\Domains\Notification\Ports;

interface NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): array;
}
