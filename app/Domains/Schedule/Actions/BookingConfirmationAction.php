<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;

class BookingConfirmationAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $schedule_id, string $decision): void
    {
        if ($decision === 'accept') {
            $this->repository->updateSchedule($schedule_id, ['status' => ScheduleStatusEnum::ACTIVE]);
        } elseif ($decision === 'reject') {
            $this->repository->updateSchedule($schedule_id, ['status' => ScheduleStatusEnum::REJECTED]);
        }
    }
}