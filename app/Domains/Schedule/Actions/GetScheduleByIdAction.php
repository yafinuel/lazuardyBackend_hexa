<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;

class GetScheduleByIdAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $scheduleId): ScheduleEntity
    {
        return $this->repository->getScheduleById($scheduleId);
    }
}