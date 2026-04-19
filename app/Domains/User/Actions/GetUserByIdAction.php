<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Entities\UserEntity;
use App\Domains\User\Ports\UserRepositoryInterface;

class GetUserByIdAction
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function execute(int $userId): UserEntity
    {
        return $this->repository->getUserById($userId);
    }
}