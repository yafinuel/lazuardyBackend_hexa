<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use App\Shared\Core\ConstantValue;
use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class MarkAsCompleteScheduleAction
{
    public function __construct(
        protected ScheduleRepositoryInterface $repository,
        protected ScheduleServicePort $service,
    ) {}

    public function execute(int $studentId,int $scheduleId)
    {
        $schedule = $this->repository->getScheduleById($scheduleId);

        // Log::info($schedule->status->value);

        if ($schedule->status !== ScheduleStatusEnum::REPORTED) {
            throw new ConflictHttpException("Only reported sessions can be marked as complete.");
        }

        if($schedule->studentId !== $studentId) {
            throw new AuthorizationException("You are not authorized to mark this session as complete.");
        }

        $this->repository->updateSchedule($scheduleId, ["status" => ScheduleStatusEnum::COMPLETED]);
        
        $tutor = $this->service->getTutorById($schedule->tutorId);
        $salary = $tutor->salary + ConstantValue::TUTOR_PRICE;
        // Log::info("Updating tutor salary. Tutor ID: {$schedule->tutorId}, New Salary: {$salary}");
        $tutorData = ["salary" => $salary];

        $this->service->updateTutorById($schedule->tutorId, $tutorData);
    }
}