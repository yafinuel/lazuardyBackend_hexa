<?php

namespace App\Domains\Notification\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): LengthAwarePaginator;
}
