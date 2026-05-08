<?php

namespace App\Domains\Student\Ports;

use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\User\Entities\UserEntity;

interface StudentServicePort
{
    public function updateUser(int $userId, array $data): void;
    public function userBiodata(int $userId): UserEntity;
    public function parentBiodata(int $userId): ParentEntity;
}