<?php

namespace App\Domains\Dashboard\Ports;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DashboardServicePort
{
    public function studentHomePage(int $studentId, int $notifPaginate, int $tutorPaginate);
    public function studentSchedulePage(int $studentId, Carbon $date, int $paginate): LengthAwarePaginator;
}