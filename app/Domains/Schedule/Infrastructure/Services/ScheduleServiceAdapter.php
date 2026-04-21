<?php

namespace App\Domains\Schedule\Infrastructure\Services;

use App\Domains\Penalty\Actions\StudentPenaltySetAction;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class ScheduleServiceAdapter implements ScheduleServicePort
{
    public function __construct(
        protected StudentPenaltySetAction $studentPenaltySetAction,
        protected GetUserByIdAction $getUserByIdAction,
    ) {}

    public function studentPenaltySet(int $userId)
    {
        return $this->studentPenaltySetAction->execute($userId);
    }

    public function getUserById(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }
}