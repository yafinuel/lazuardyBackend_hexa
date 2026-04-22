<?php

namespace App\Domains\Schedule\Ports;

use App\Domains\User\Entities\UserEntity;
use Carbon\Carbon;

interface ScheduleServicePort
{
    public function userPenaltySet(int $userId);
    public function getUserById(int $userId): UserEntity;
}