<?php

namespace App\Domains\Notification\Actions;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;

class ClearFcmTokenAction
{
    public function __construct(protected NotificationRepositoryInterface $repository) {}

    public function execute(int $userId, ?string $deviceId = null): void
    {
        $this->repository->clearFcmToken($userId, $deviceId);
    }
}