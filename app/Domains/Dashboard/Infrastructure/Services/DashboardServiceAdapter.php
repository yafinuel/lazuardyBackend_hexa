<?php

namespace App\Domains\Dashboard\Infrastructure\Services;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\Notification\Actions\GetNotifByUserIdAction;
use App\Domains\Penalty\Actions\GetUserWarningAction;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\Schedule\Actions\GetStudentSchedulesByDateAction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardServiceAdapter implements DashboardServicePort
{
    public function __construct(
        protected GetStudentByIdAction $meAction,
        protected GetTutorByCriteria $tutorAction,
        protected GetNotifByUserIdAction $notifAction,
        protected FileRepositoryInterface $fileRepository,
        protected FileStorageInterface $storage,
        protected GetUserWarningAction $userWarningAction,
        protected GetStudentSchedulesByDateAction $scheduleAction,
    ) {}

    public function studentHomePage(int $studentId, int $notifPaginate, int $tutorPaginate)
    {
        $me = $this->meAction->execute($studentId);
        $notifications = $this->notifAction->execute($studentId, $notifPaginate);
        $tutors = $this->tutorAction->execute([], $tutorPaginate);
        $warning = $this->userWarningAction->execute($studentId);
        
        return [
            'me' => $me,
            'notification' => $notifications,
            'tutor_recomendation' => $tutors,
            'warning' => $warning,
        ];
    }

    public function studentSchedulePage(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator
    {
        return $this->scheduleAction->execute($studentId, $date, $paginate);
    }
}