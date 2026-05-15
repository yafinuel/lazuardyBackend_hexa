<?php

namespace App\Domains\Presence\Actions;

use App\Domains\Presence\Ports\PresenceRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;

class CreatePresenceAction
{
    public function __construct(
        protected PresenceRepositoryInterface $presenceRepository,
        protected ScheduleRepositoryInterface $scheduleRepository,
    ) {}

    public function execute(int $scheduleId, int $tutorId, int $studentId, string $topic, string $notes): void
    {
        $schedule = $this->scheduleRepository->getScheduleById($scheduleId);

        if ($schedule->tutorId !== $tutorId) {
            throw new AuthorizationException('Schedule does not belong to this tutor.');
        }

        $this->presenceRepository->createPresence($scheduleId, $tutorId, $studentId, $topic, $notes);
        $this->scheduleRepository->updateSchedule($scheduleId, ['status' => ScheduleStatusEnum::COMPLETED]);
    }
}