<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Infrastructure\Services\ScheduleServiceAdapter;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Shared\Enums\RoleEnum;
use App\Shared\Enums\ScheduleStatusEnum;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CancelScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServiceAdapter $service, protected StudentRepositoryInterface $studentRepository) {}

    public function execute(int $userId, array $data): bool
    {
        $user = $this->service->getUserById($userId);
        $schedule = $this->repository->getScheduleById($data['schedule_id']);

        if ($schedule->tutorId !== $userId && $schedule->studentId !== $userId) {
            throw new AuthorizationException('Schedule does not belong to this user.');
        }

        if ($schedule->status !== ScheduleStatusEnum::ACTIVE) {
            throw new ConflictHttpException('Schedule is already ' . $schedule->status->value . '.');
        }

        $timeValue = $schedule->time ?? $schedule->startTime ?? null;
        if (is_string($timeValue)) {
            $timeString = $timeValue;
        } elseif ($timeValue instanceof \DateTimeInterface) {
            $timeString = $timeValue->format('H:i:s');
        } else {
            $timeString = '00:00:00';
        }

        $tz = config('app.timezone') ?: 'UTC';
        $scheduleStartAt = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->date->toDateString() . ' ' . $timeString, $tz);
        $now = Carbon::now($tz);
        $minutesUntilStart = $now->diffInMinutes($scheduleStartAt, false);

        Log::info('CancelScheduleAction: time debug', ['user_id' => $userId, 'schedule_id' => $data['schedule_id'], 'now' => $now->toDateTimeString(), 'schedule_start_at' => $scheduleStartAt->toDateTimeString(), 'app_timezone' => $tz]);

        if ($user->role === RoleEnum::TUTOR) {
            if ($minutesUntilStart >= 0) {
                if ($minutesUntilStart < 12 * 60) {
                    Log::info("CancelScheduleAction: applying penalty check", ['user_id' => $userId, 'schedule_id' => $data['schedule_id'], 'minutes_until_start' => $minutesUntilStart, 'role' => $user->role->value]);
                    $this->service->userPenaltySet($userId);
                    // refresh user after potential DB update
                    $user = $this->service->getUserById($userId);
                }
            }

            Log::info("CancelScheduleAction: cek log diluar pinalti", ['user_id' => $userId, 'schedule_id' => $data['schedule_id'], 'minutes_until_start' => $minutesUntilStart, 'role' => $user->role->value, 'warning' => $user->warning, 'sanction' => $user->sanction]);

            $notificationData = [
                'title' => 'Schedule Canceled',
                'body' => 'Your schedule has been canceled by tutor ' . $schedule->tutorName,
                'data' => [
                    'schedule_id' => $data['schedule_id'],
                    'tutor_name' => $schedule->tutorName,
                    'subject_name' => $schedule->subjectName,
                    'date' => $schedule->date->toDateString(),
                    'time' => $schedule->startTime->format('H:i') . ' - ' . $schedule->endTime->format('H:i'),
                ],
            ];

            $this->service->pushNotificationToUser($schedule->studentId, $notificationData);
        } else if ($user->role === RoleEnum::STUDENT) {
            $student = $this->studentRepository->getStudentById($userId);
            if ($minutesUntilStart >= 0) {
                if ($minutesUntilStart > 12 * 60) {
                    $this->studentRepository->update($student->id, ['session' => $student->session + 1]);
                } else {
                    Log::info("CancelScheduleAction: applying penalty check (student)", ['user_id' => $userId, 'schedule_id' => $data['schedule_id'], 'minutes_until_start' => $minutesUntilStart, 'role' => $user->role->value, 'warning' => $user->warning, 'sanction' => $user->sanction]);
                    $this->service->userPenaltySet($userId);
                }
            }

            Log::info("CancelScheduleAction: cek log diluar pinalti", ['user_id' => $userId, 'schedule_id' => $data['schedule_id'], 'minutes_until_start' => $minutesUntilStart, 'role' => $user->role->value, 'warning' => $user->warning, 'sanction' => $user->sanction]);
            $notificationData = [
                'title' => 'Schedule Canceled',
                'body' => 'Your schedule has been canceled by student ' . $schedule->studentName,
                'data' => [
                    'schedule_id' => $data['schedule_id'],
                    'tutor_name' => $schedule->tutorName,
                    'subject_name' => $schedule->subjectName,
                    'date' => $schedule->date->toDateString(),
                    'time' => $schedule->startTime->format('H:i') . ' - ' . $schedule->endTime->format('H:i'),
                ],
            ];

            $this->service->pushNotificationToUser($schedule->tutorId, $notificationData);
        }
        // Log::info("User {$userId} canceled schedule {$data['schedule_id']} with {$minutesUntilStart} minutes until start. User role: {$user->role->value}");
        return $this->repository->cancelSchedule($data['schedule_id'], $data['reason']);
    }
}