<?php

namespace App\Domains\Presence\Ports;

use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\User\Entities\UserEntity;

interface PresenceServicePort
{
    public function userBiodata(int $userId): UserEntity;
    public function parentBiodata(int $parentId): ParentEntity;
    public function scheduleDetail(int $scheduleId): ScheduleEntity;
}