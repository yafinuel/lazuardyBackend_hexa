<?php

namespace App\Domains\Notification\Actions;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;

class UpdateOrCreateFcmTokenAction
{
    public function __construct(protected NotificationRepositoryInterface $repository) {}

    public function execute(int $userId, string $deviceId, string $token, ?string $platform = null): void
    {
        $this->repository->updateOrCreateFcmToken($userId, $deviceId, $token, $platform);
    }
}