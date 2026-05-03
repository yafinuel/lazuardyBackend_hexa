<?php

namespace App\Domains\Report\Infrastructure\Repository;

use App\Domains\Report\Ports\ReportRepositoryInterface;
use App\Models\Presence;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentReportRepository implements ReportRepositoryInterface
{
    public function getAllReportsByStudentId(int $studentId, int $perPage = 10): LengthAwarePaginator
    {
        $student = Student::findOrFail($studentId);
        
        return $student->presences()->with(['schedule', 'tutor'])->paginate($perPage);
    }

    public function createPresence(int $scheduleId, int $tutorId, int $studentId, string $topic, string $notes): void
    {
        Presence::create([
            'schedule_id' => $scheduleId,
            'tutor_id' => $tutorId,
            'student_id' => $studentId,
            'topic' => $topic,
            'notes' => $notes,
        ]);
    }
}