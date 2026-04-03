<?php

namespace App\Domains\Subject\Ports;

interface SubjectRepositoryInterface
{
    public function getAllSubjects(): array;
    public function getSubjectByClass(int $classId): array;
    public function getUniqueSubjectByLevel(string $level): array;
}
