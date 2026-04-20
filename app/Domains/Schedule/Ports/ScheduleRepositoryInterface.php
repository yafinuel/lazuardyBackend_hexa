<?php

namespace App\Domains\Schedule\Ports;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool;
    public function getStudentSchedule(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator;
}