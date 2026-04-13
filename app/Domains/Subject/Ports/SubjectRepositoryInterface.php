<?php

namespace App\Domains\Subject\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SubjectRepositoryInterface
{
    public function getAllSubjects(): LengthAwarePaginator;
    public function getSubjectByClass(int $classId): LengthAwarePaginator;
    public function getUniqueSubjectByLevel(string $level): LengthAwarePaginator;
}
