<?php

namespace App\Domains\Notification\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): LengthAwarePaginator;
    public function pushNotificationToUser(int $userId, array $notificationData): void;
    public function clearFcmToken(int $userId, ?string $deviceId = null): void;
    public function updateOrCreateFcmToken(int $userId, string $deviceId, string $token, ?string $platform = null): void;
}
