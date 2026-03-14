<?php

namespace App\Domains\UserProfile\Student\Ports;

use App\Domains\UserProfile\Student\Entities\StudentEntity;

interface StudentRepositoryInterface
{
    public function getStudentProfile(int $studentId): ?StudentEntity;
    public function updateStudentProfile(int $studentId, array $data): void;
}
