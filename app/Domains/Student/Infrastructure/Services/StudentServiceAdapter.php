<?php

namespace App\Domains\Student\Infrastructure\Services;

use App\Domains\Parent\Actions\GetParentByIdAction;
use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\Student\Ports\StudentServicePort;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Actions\UpdateUserAction;
use App\Domains\User\Entities\UserEntity;

class StudentServiceAdapter implements StudentServicePort
{
    public function __construct(
        protected UpdateUserAction $updateUserAction,
        protected GetUserByIdAction $getUserByIdAction,
        protected GetParentByIdAction $getParentByIdAction,
    ) {}

    public function updateUser(int $userId, array $data): void
    {
        $this->updateUserAction->execute($userId, $data);
    }

    public function userBiodata(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }

    public function parentBiodata(int $userId): ParentEntity
    {
        return $this->getParentByIdAction->execute($userId);
    }
}