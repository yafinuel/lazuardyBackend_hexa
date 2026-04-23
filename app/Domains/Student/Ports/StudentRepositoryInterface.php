<?php

namespace App\Domains\Student\Ports;

use App\Domains\Student\Entities\StudentEntity;

interface StudentRepositoryInterface
{
    public function getStudentById(int $studentId): ?StudentEntity;
    public function createStudent(int $userId, array $data): int;
    public function update(int $userId, array $data): void;
}