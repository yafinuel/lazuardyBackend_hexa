<?php

namespace App\Domains\Schedule\Ports;

use App\Domains\Schedule\Entities\ScheduleEntity;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool;
    public function getSchedulesByDate(int $userId, Carbon $date, int $paginate = 10): LengthAwarePaginator;
    public function getScheduleById(int $scheduleId): ScheduleEntity;
    public function cancelSchedule(int $scheduleId, string $reason): bool;
    public function createMeetingSchedule(array $data): void;
    public function getTutorSchedulesByDay(int $tutorId, ?string $day, int $paginate = 10): LengthAwarePaginator;
    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator;
    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int;
    public function getFilteredSchedulesByTutorId(int $tutorId, array $filters, int $paginate = 10): LengthAwarePaginator;
}