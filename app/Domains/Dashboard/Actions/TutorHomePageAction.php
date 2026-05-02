<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class TutorHomePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $tutorId, int $notifPaginate = 2)
    {
        $tutorBiodata = $this->service->tutorBiodata($tutorId);
        $thisMonthSchedules = $this->service->getSchedulesThisMonthByTutorId($tutorId);
        $notificationData = $this->service->getNotification($tutorId, $notifPaginate);
        $warning = $this->service->getUserWarning($tutorId);
        $studentTotal = $this->service->getStudentCountThisMonthSchedulesByTutorId($tutorId);
        $salaryStats = $this->service->salaryStats($tutorId);

        


        return [
            'tutor' => $tutorBiodata,
            'salary' => $tutorBiodata->salary,
            'salary_stats' => $salaryStats,
            'schedules_total' => $thisMonthSchedules->total(),
            'student_total' => $studentTotal,
            'notification_data' => $notificationData,
            'warning' => $warning,
        ];
    }
}