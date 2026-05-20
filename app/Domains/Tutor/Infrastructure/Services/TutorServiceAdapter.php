<?php

namespace App\Domains\Tutor\Infrastructure\Services;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Domains\Tutor\Ports\TutorServicePort;
use App\Domains\User\Actions\UpdateUserAction;

class TutorServiceAdapter implements TutorServicePort
{
    public function __construct(
        protected UpdateUserAction $updateUserAction,
        protected NotificationRepositoryInterface $notificationRepository
    ) {}

    public function updateUser(int $userId, array $data): void
    {
        $this->updateUserAction->execute($userId, $data);
    }
    
    public function pushNotificationToUser(int $userId, array $notificationData): void
    {
        $this->notificationRepository->pushNotificationToUser($userId, $notificationData);
    }
}