<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class StudentHomePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $studentId,  int $notifPaginate = 2, int $tutorPaginate = 4)
    {
        return $this->service->studentHomePage($studentId, $notifPaginate, $tutorPaginate);
    }
}