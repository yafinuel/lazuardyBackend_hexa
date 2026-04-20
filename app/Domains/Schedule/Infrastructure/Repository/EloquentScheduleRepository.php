<?php

namespace App\Domains\Schedule\Infrastructure\Repository;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Models\Student;
use App\Models\Tutor;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentScheduleRepository implements ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        $tutor->tutorSchedules()->createMany($data);
        return true;
    }

    public function getStudentSchedule(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator
    {
        $student = Student::where('user_id', $studentId)->firstOrFail();
        
        return $student->schedules()
            ->whereDate('date', $date->toDateString())
            ->with('tutor.user', 'subject')
            ->paginate($paginate);
    }
}