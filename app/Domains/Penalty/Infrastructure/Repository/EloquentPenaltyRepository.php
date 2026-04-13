<?php

namespace App\Domains\Penalty\Infrastructure\Repository;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use App\Models\Student;
use App\Models\Tutor;

class EloquentPenaltyRepository implements PenaltyRepositoryInterface
{
    public function addWarning(string $userId): void
    {
        $student = Student::findOrFail($userId);
        if ($student) {
            $student->warning += 1;
            $student->save();
        }
    }
    public function setSanction(string $userId, ?string $sanctionEndDate): void
    {
        $student = Student::findOrFail($userId);
        if ($student) {
            $student->sanction = $sanctionEndDate;
            $student->save();
        }
    }
}