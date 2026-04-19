<?php

namespace App\Domains\User\Ports;

use App\Domains\User\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function createUser(array $data): int;
    public function getUserById(int $userId): UserEntity;
    public function getUserByEmail(string $email): UserEntity;
    public function resetPassword(string $email, string $newPassword): void;
    public function updateSocialId(int $userId, string $provider, string $providerId): void;
}