<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BookingConfirmationAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServicePort $service) {}

    public function execute(int $tutorId, int $schedule_id, string $decision): void
    {
        $schedule = $this->repository->getScheduleById($schedule_id);

        if ($schedule->tutorId !== $tutorId) {
            throw new AuthorizationException('Schedule does not belong to this tutor.');
        }

        if ($schedule->status !== ScheduleStatusEnum::PENDING) {
            throw new ConflictHttpException('Schedule is not in a pending state.');
        }

        if ($decision === 'accept') {
            $this->repository->updateSchedule($schedule_id, ['status' => ScheduleStatusEnum::ACTIVE]);

            $notificationData = [
                'title' => 'Booking Confirmed',
                'body' => 'Your booking has been accepted.',
                'data' => [
                    'schedule_id' => $schedule_id,
                    'tutor_name' => $schedule->tutorName,
                    'subject_name' => $schedule->subjectName,
                    'date' => $schedule->date->toDateString(),
                    'time' => $schedule->startTime->format('H:i') . ' - ' . $schedule->endTime->format('H:i'),
                ],
            ];

            $this->service->pushNotificationToUser($schedule->studentId, $notificationData);
        }

        if ($decision === 'reject') {
            $this->repository->updateSchedule($schedule_id, ['status' => ScheduleStatusEnum::REJECTED]);
            $notificationData = [
                'title' => 'Booking Rejected',
                'body' => 'Your booking has been rejected.',
                'data' => [
                    'schedule_id' => $schedule_id,
                    'tutor_name' => $schedule->tutorName,
                    'subject_name' => $schedule->subjectName,
                    'date' => $schedule->date->toDateString(),
                    'time' => $schedule->startTime->format('H:i') . ' - ' . $schedule->endTime->format('H:i'),
                ],
            ];
            $this->service->pushNotificationToUser($schedule->studentId, $notificationData);
        }
    }
}