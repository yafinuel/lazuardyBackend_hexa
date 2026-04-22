<?php

namespace App\Domains\Schedule\Ports;

use App\Domains\Schedule\Entities\ScheduleEntity;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool;
    public function getStudentSchedulesByDate(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator;
    public function getScheduleById(int $scheduleId): ScheduleEntity;
    public function cancelSchedule(int $scheduleId, string $reason): bool;
}