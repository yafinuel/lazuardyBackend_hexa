<?php

namespace App\Domains\Notification\Ports;

interface NotificationRepositoryInterface
{
    public function getAllUserNotification(int $userId, int $paginate): array;
}
