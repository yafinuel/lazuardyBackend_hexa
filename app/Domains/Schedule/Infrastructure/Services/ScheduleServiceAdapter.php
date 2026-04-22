<?php

namespace App\Domains\Schedule\Infrastructure\Services;

use App\Domains\Penalty\Actions\UserPenaltySetAction;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class ScheduleServiceAdapter implements ScheduleServicePort
{
    public function __construct(
        protected UserPenaltySetAction $userPenaltySetAction,
        protected GetUserByIdAction $getUserByIdAction,
    ) {}

    public function userPenaltySet(int $userId)
    {
        return $this->userPenaltySetAction->execute($userId);
    }

    public function getUserById(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }
}