<?php

namespace App\Domains\Commerce\Infrastructure\Services;

use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Domains\Package\Actions\GetPackageByIdAction;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class CommerceServiceAdapter implements CommerceServicePort
{
    public function __construct(
        protected GetPackageByIdAction $getPackageByIdAction,
        protected GetUserByIdAction $getUserByIdAction
    ) {}

    public function getPackageByIdAction(int $packageId)
    {
        return $this->getPackageByIdAction->execute($packageId);
    }

    public function getUserByIdAction(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }
}