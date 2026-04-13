<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class StudentHomePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $studentId,  int $notifPaginate = 2, int $tutorPaginate = 4)
    {
        $result = $this->service->studentHomePage($studentId, $notifPaginate, $tutorPaginate);
        return [
            'user_name' => $result['me']->name,
            'warning' => $result['me']->warning,
            'sanction' => $result['me']->sanction,
            'session' => $result['me']->session,
            'notification' => $result['notification'],
            'tutor_recomendation' => $result['tutor_recomendation'],
        ];
    }
}