<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Infrastructure\Services\ScheduleServiceAdapter;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;

class CancelScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServiceAdapter $service) {}

    public function execute(int $userId, int $scheduleId): bool
    {
        $user = $this->service->getUserById($userId);
        $schedule = $this->repository->getScheduleById($scheduleId);

        if ($user->role === RoleEnum::STUDENT) {
            $scheduleStartAt = $schedule->date->copy()->setTimeFrom($schedule->startTime);
            $hoursUntilStart = Carbon::now()->diffInHours($scheduleStartAt, false);

            if ($hoursUntilStart >= 0) {
                if ($hoursUntilStart <= 12) {
                    $this->service->studentPenaltySet($userId);
                } else {
                    // Untuk mengembalikan jatah sesi
                }
            }
        }

        return $this->repository->cancelSchedule($scheduleId);
    }
}