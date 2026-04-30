<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class TutorHomePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $tutorId, int $notifPaginate = 2)
    {
        $tutor = $this->service->tutorBiodata($tutorId);
        $thisMonthSchedules = $this->service->getSchedulesThisMonthByTutorId($tutorId);
        $notificationData = $this->service->getNotification($tutorId, $notifPaginate);
        $warning = $this->service->getUserWarning($tutorId);

        $schedulesTotal = $thisMonthSchedules["total"];
        $studentTotal = $thisMonthSchedules["student_count"];


        return [
            'tutor' => $tutor,
            'schedules_total' => $schedulesTotal,
            'student_total' => $studentTotal,
            'notification_data' => $notificationData,
            'warning' => $warning,
        ];
    }
}