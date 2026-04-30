<?php

namespace App\Domains\Dashboard\Infrastructure\Services;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\Notification\Actions\GetNotifByUserIdAction;
use App\Domains\Penalty\Actions\GetUserWarningAction;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\Schedule\Actions\GetSchedulesThisMonthByTutorIdAction;
use App\Domains\Schedule\Actions\GetStudentSchedulesByDateAction;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Entities\TutorEntity;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardServiceAdapter implements DashboardServicePort
{
    public function __construct(
        protected GetStudentByIdAction $studentBiodata,
        protected GetTutorByIdAction $tutorBiodata,
        protected GetTutorByCriteria $tutorAction,
        protected GetNotifByUserIdAction $notifAction,
        protected FileRepositoryInterface $fileRepository,
        protected FileStorageInterface $storage,
        protected GetUserWarningAction $userWarningAction,
        protected GetStudentSchedulesByDateAction $scheduleAction,
        protected GetSchedulesThisMonthByTutorIdAction $getSchedulesThisMonthByTutorIdAction,
    ) {}

    public function getUserWarning(int $studentId): int
    {
        return $this->userWarningAction->execute($studentId);
        
    }

    public function studentSchedulePage(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator
    {
        return $this->scheduleAction->execute($studentId, $date, $paginate);
    }

    public function studentBiodata(int $studentId): StudentEntity
    {
        return $this->studentBiodata->execute($studentId);
    }

    public function tutorBiodata(int $tutorId): TutorEntity
    {
        return $this->tutorBiodata->execute($tutorId);
    }

    public function getNotification(int $userId, int $paginate): LengthAwarePaginator
    {
        return $this->notifAction->execute($userId, $paginate);
    }
    
    public function getTutorByCriteria(array $criteria, int $paginate)
    {
        return $this->tutorAction->execute($criteria, $paginate);
    }

    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): array
    {
        return $this->getSchedulesThisMonthByTutorIdAction->execute($tutorId, $paginate);
    }
}