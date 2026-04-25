<?php

namespace App\Domains\Report\Infrastructure\Repository;

use App\Domains\Report\Ports\ReportRepositoryInterface;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentReportRepository implements ReportRepositoryInterface
{
    public function getAllReportsByStudentId(int $studentId, int $perPage = 10): LengthAwarePaginator
    {
        $student = Student::findOrFail($studentId);
        
        return $student->presences()->with(['schedule', 'tutor'])->paginate($perPage);
    }
}