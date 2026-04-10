<?php

namespace App\Domains\Dashboard\Ports;

interface DashboardServicePort
{
    public function studentHomePage(int $studentId, int $notifPaginate, int $tutorPaginate);
}