<?php

namespace App\Domains\User\Ports;

use App\Domains\User\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function createUser(array $data): int;
    public function getUserById(int $userId): UserEntity;
}