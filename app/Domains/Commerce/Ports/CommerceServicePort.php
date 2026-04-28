<?php

namespace App\Domains\Commerce\Ports;

use App\Domains\User\Entities\UserEntity;

interface CommerceServicePort
{
    public function getPackageByIdAction(int $packageId);
    public function getUserByIdAction(int $userId): UserEntity;
}