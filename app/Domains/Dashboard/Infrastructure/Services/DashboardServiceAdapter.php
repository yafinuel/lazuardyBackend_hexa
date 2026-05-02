<?php

namespace App\Domains\Dashboard\Infrastructure\Services;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\Notification\Actions\GetNotifByUserIdAction;
use App\Domains\Penalty\Actions\GetUserWarningAction;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\Schedule\Actions\GetSchedulesByDateAction;
use App\Domains\Schedule\Actions\GetSchedulesThisMonthByTutorIdAction;
use App\Domains\Schedule\Actions\GetStudentCountThisMonthSchedulesByTutorId;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Entities\TutorEntity;
use App\Shared\Core\ConstantValue;
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
        protected GetSchedulesByDateAction $schedulesByDateAction,
        protected GetSchedulesThisMonthByTutorIdAction $getSchedulesThisMonthByTutorIdAction,
        protected GetStudentCountThisMonthSchedulesByTutorId $getStudentCountThisMonthSchedulesByTutorId,
    ) {}

    public function getUserWarning(int $studentId): int
    {
        return $this->userWarningAction->execute($studentId);
        
    }

    public function schedulesByDate(int $userId, Carbon $date, int $paginate = 10): LengthAwarePaginator
    {
        return $this->schedulesByDateAction->execute($userId, $date, $paginate);
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

    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator
    {
        return $this->getSchedulesThisMonthByTutorIdAction->execute($tutorId, $paginate);
    }

    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int
    {
        return $this->getStudentCountThisMonthSchedulesByTutorId->execute($tutorId);
    }

    public function salaryStats(int $tutorId): int
    {
        $totalSchedulesThisMonth = $this->getSchedulesThisMonthByTutorIdAction->execute($tutorId, 10)->total();
        $price = ConstantValue::TUTOR_PRICE;
        return $totalSchedulesThisMonth * $price;
    }
}