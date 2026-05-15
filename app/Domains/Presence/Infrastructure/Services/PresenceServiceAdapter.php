<?php

namespace App\Domains\Presence\Infrastructure\Services;

use App\Domains\Parent\Actions\GetParentByIdAction;
use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\Presence\Ports\PresenceServicePort;
use App\Domains\Schedule\Actions\GetScheduleByIdAction;
use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class PresenceServiceAdapter implements PresenceServicePort
{
    public function __construct(
        protected GetUserByIdAction $getUserById,
        protected GetParentByIdAction $getParentById,
        protected GetScheduleByIdAction $getScheduleById,
    ) {}
    
    public function userBiodata(int $userId): UserEntity
    {
        return $this->getUserById->execute($userId);;
    }

    public function parentBiodata(int $parentId): ParentEntity
    {
        return $this->getParentById->execute($parentId);
    }
    
    public function scheduleDetail(int $scheduleId): ScheduleEntity
    {
        return $this->getScheduleById->execute($scheduleId);
    }
}