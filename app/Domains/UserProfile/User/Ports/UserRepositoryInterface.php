<?php

namespace App\Domains\UserProfile\User\Ports;

interface UserRepositoryInterface
{
    public function updateRawProfilePhoto(int $userId, string $url);
    public function updateProfilePhoto(int $userId, array $data);
}
