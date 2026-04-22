<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Infrastructure\Services\ScheduleServiceAdapter;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServiceAdapter $service, protected StudentRepositoryInterface $studentRepository) {}

    public function execute(int $userId, array $data): bool
    {
        $user = $this->service->getUserById($userId);
        $schedule = $this->repository->getScheduleById($data['schedule_id']);

        $scheduleStartAt = $schedule->date->copy()->setTimeFrom($schedule->startTime);
        $minutesUntilStart = Carbon::now()->diffInMinutes($scheduleStartAt, false);

        if ($user->role === RoleEnum::TUTOR) {
            if ($minutesUntilStart >= 0) {
                if ($minutesUntilStart < 12 * 60) {
                    $this->service->userPenaltySet($userId);
                } 
            }
        } else if ($user->role === RoleEnum::STUDENT) {
            $student = $this->studentRepository->getStudentById($userId);
            if ($minutesUntilStart >= 0) {
                if ($minutesUntilStart > 12 * 60) {
                    $this->studentRepository->updateStudent($student->id, ['session' => $student->session + 1]);
                }
            }
        }
        Log::info("User {$userId} canceled schedule {$data['schedule_id']} with {$minutesUntilStart} minutes until start. User role: {$user->role->value}");
        return $this->repository->cancelSchedule($data['schedule_id'], $data['reason']);
    }
}