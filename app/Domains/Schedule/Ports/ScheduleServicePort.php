<?php

namespace App\Domains\Schedule\Ports;

use App\Domains\Student\Entities\StudentEntity;
use App\Domains\User\Entities\UserEntity;
use Carbon\Carbon;

interface ScheduleServicePort
{
    public function userPenaltySet(int $userId);
    public function getUserById(int $userId): UserEntity;
    public function getStudentById(int $studentId): StudentEntity;
    public function updateStudent(int $studentId, array $data): void;
}