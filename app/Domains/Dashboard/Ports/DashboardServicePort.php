<?php

namespace App\Domains\Dashboard\Ports;

use App\Domains\Parent\Entities\ParentEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DashboardServicePort
{
    public function getUserWarning(int $studentId): int;
    public function studentBiodata(int $studentId);
    public function tutorBiodata(int $tutorId);
    public function parentBiodata(int $parentId): ParentEntity;
    public function getNotification(int $userId, int $paginate): LengthAwarePaginator;
    public function getTutorByCriteria(array $criteria, int $paginate);
    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator;
    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int;
    public function salaryStats(int $tutorId): int;
    public function getSchedulesByUserId(int $userId, ?array $filters, int $paginate = 10): LengthAwarePaginator;
    public function getUserById(int $userId);
}