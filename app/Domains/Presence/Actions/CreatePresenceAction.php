<?php

namespace App\Domains\Presence\Actions;

use App\Domains\Presence\Ports\PresenceRepositoryInterface;
use App\Domains\Presence\Ports\PresenceServicePort;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CreatePresenceAction
{
    public function __construct(
        protected PresenceRepositoryInterface $presenceRepository,
        protected ScheduleRepositoryInterface $scheduleRepository,
        protected PresenceServicePort $presenceService
    ) {}

    public function execute(int $scheduleId, int $tutorId, string $topic, string $notes): void
    {
        $schedule = $this->scheduleRepository->getScheduleById($scheduleId);

        if ($schedule->tutorId !== $tutorId) {
            throw new AuthorizationException('Schedule does not belong to this tutor.');
        }

        if ($schedule->status !== ScheduleStatusEnum::ACTIVE) {
            throw new ConflictHttpException("Only active schedules can be marked as complete.");
        }

        $this->presenceRepository->createPresence($scheduleId, $tutorId, $schedule->studentId, $topic, $notes);
        $this->scheduleRepository->updateSchedule($scheduleId, ['status' => ScheduleStatusEnum::REPORTED]);

        $notificationData = [
            'title' => 'Schedule Completed',
            'body' => 'Your schedule has been marked as completed by tutor ' . $schedule->tutorName,
            'data' => [
                'schedule_id' => $scheduleId,
                'tutor_name' => $schedule->tutorName,
                'subject_name' => $schedule->subjectName,
                'date' => $schedule->date->toDateString(),
                'time' => $schedule->startTime->format('H:i') . ' - ' . $schedule->endTime->format('H:i'),
            ],
        ];

        $this->presenceService->pushNotificationToUser($schedule->studentId, $notificationData);
    }
}