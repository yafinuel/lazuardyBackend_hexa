<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;

class MarkAsCompleteScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $scheduleId)
    {
        return $this->repository->updateSchedule($scheduleId, ["status" => ScheduleStatusEnum::COMPLETED]);
    }
}