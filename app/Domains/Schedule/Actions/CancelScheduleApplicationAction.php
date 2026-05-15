<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CancelScheduleApplicationAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $studentId, int $scheduleId): void
    {
        $schedule = $this->repository->getScheduleById($scheduleId);

        if ($schedule->studentId !== $studentId) {
            throw new AuthorizationException('Schedule does not belong to this student.');
        }

        if ($schedule->status !== ScheduleStatusEnum::PENDING) {
            throw new ConflictHttpException('Schedule is not in a pending state.');
        }

        $this->repository->updateSchedule($scheduleId, ['status' => ScheduleStatusEnum::CANCELLED]);
    }
}