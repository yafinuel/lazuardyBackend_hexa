<?php

namespace App\Domains\Dashboard\Infrastructure\Services;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\Notification\Actions\GetNotifByUserIdAction;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Tutor\Actions\GetTutorByCriteria;

class DashboardServiceAdapter implements DashboardServicePort
{
    public function __construct(
        protected GetStudentByIdAction $meAction,
        protected GetNotifByUserIdAction $notifAction,
        protected GetTutorByCriteria $tutorAction,
    ) {}
    public function studentHomePage(int $studentId, int $notifPaginate, int $tutorPaginate)
    {
        $me = $this->meAction->execute($studentId);
        $notifications = $this->notifAction->execute($studentId, $notifPaginate);
        $tutors = $this->tutorAction->execute([], $tutorPaginate);
        
        return [
            'user_name' => $me->name,
            'session' => $me->session,
            'notification' => $notifications,
            'tutor_recomendation' => $tutors
        ];
    }
}