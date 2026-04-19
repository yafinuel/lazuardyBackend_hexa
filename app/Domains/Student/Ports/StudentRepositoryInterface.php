<?php

namespace App\Domains\Student\Ports;

use App\Domains\Student\Entities\StudentEntity;

interface StudentRepositoryInterface
{
    public function getStudentById(int $studentId): ?StudentEntity;
    public function updateStudentProfile(int $studentId, array $data): void;
    public function createStudent(int $userId, array $data): int;
}