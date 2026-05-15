<?php

namespace App\Domains\Review\Infrastructure\Services;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Domains\Review\Ports\ReviewServicePort;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class ReviewServiceAdapter implements ReviewServicePort
{
    public function __construct(
        protected GetUserByIdAction $getUserByIdAction,
        protected NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function getUserById(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }

    public function pushNotificationToUser(int $userId, array $notificationData): void
    {
        $this->notificationRepository->pushNotificationToUser($userId, $notificationData);
    }
}