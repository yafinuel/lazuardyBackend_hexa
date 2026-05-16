<?php

namespace App\Domains\Commerce\Ports;

use App\Domains\User\Entities\UserEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommerceServicePort
{
    public function getPackageByIdAction(int $packageId);
    public function getUserByIdAction(int $userId): UserEntity;
    public function getSchedulesThisMonthByTutorId(int $tutorId): LengthAwarePaginator;
    public function updateStudentSession(int $studentId, int $sessionToAdd = 1);
    public function getTutorById(int $tutorId);
    public function updateTutorById(int $tutorId, array $data);
}