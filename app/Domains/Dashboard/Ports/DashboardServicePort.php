<?php

namespace App\Domains\Dashboard\Ports;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DashboardServicePort
{
    public function getUserWarning(int $studentId): int;
    public function schedulesByDate(int $userId, Carbon $date, int $paginate): LengthAwarePaginator;
    public function studentBiodata(int $studentId);
    public function tutorBiodata(int $tutorId);
    public function getNotification(int $userId, int $paginate): LengthAwarePaginator;
    public function getTutorByCriteria(array $criteria, int $paginate);
    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator;
    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int;
    public function salaryStats(int $tutorId): int;
}