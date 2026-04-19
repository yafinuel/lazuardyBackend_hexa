<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Entities\UserEntity;
use App\Domains\User\Ports\UserRepositoryInterface;

class GetUserByEmailAction
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function execute(string $email): UserEntity
    {
        return $this->repository->getUserByEmail($email);
    }
}