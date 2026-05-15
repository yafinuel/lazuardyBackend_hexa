<?php

namespace App\Domains\Review\Ports;

interface ReviewServicePort
{
    public function getUserById(int $userId): \App\Domains\User\Entities\UserEntity;

    public function pushNotificationToUser(int $userId, array $notificationData): void;
}