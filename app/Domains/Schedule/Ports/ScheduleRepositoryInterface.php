<?php

namespace App\Domains\Schedule\Ports;

use App\Domains\Schedule\Entities\ScheduleEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool;
    public function getScheduleById(int $scheduleId): ScheduleEntity;
    public function cancelSchedule(int $scheduleId, string $reason): bool;
    public function createMeetingSchedule(array $data): void;
    public function getTutorSchedulesByDay(int $tutorId, ?string $day, int $paginate = 10): LengthAwarePaginator;
    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator;
    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int;
    public function updateSchedule(int $scheduleId, array $data): bool;
    public function updateTutorSchedule(int $tutorId, array $data): bool;
    public function getSchedulesByUserId(int $userId, ?array $filters, int $paginate = 10): LengthAwarePaginator;
}