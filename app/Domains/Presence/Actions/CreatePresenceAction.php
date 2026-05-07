<?php

namespace App\Domains\Presence\Actions;

use App\Domains\Presence\Ports\PresenceRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;

class CreatePresenceAction
{
    public function __construct(protected PresenceRepositoryInterface $presenceRepository, protected ScheduleRepositoryInterface $scheduleRepository) {}

    public function execute(int $scheduleId, int $tutorId, int $studentId, string $topic, string $notes): void
    {
        $this->presenceRepository->createPresence($scheduleId, $tutorId, $studentId, $topic, $notes);
        $this->scheduleRepository->updateSchedule($scheduleId, ['status' => ScheduleStatusEnum::COMPLETED]);
    }
}