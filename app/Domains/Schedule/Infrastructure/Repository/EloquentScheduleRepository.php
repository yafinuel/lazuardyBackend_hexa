<?php

namespace App\Domains\Schedule\Infrastructure\Repository;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Tutor;
use App\Shared\Enums\ScheduleStatusEnum;
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

    public function getStudentSchedulesByDate(int $studentId, Carbon $date, int $paginate = 10): LengthAwarePaginator
    {
        $student = Student::where('user_id', $studentId)->firstOrFail();
        
        return $student->schedules()
            ->whereDate('date', $date->toDateString())
            ->with('tutor.user', 'subject')
            ->paginate($paginate);
    }

    public function getScheduleById(int $scheduleId): ScheduleEntity
    {
        $schedule = Schedule::with('tutor.user', 'student.user', 'subject')
            ->findOrFail($scheduleId);

        return new ScheduleEntity(
            id: $schedule->id,
            tutorId: $schedule->tutor_id,
            studentId: $schedule->student_id,
            subjectId: $schedule->subject_id,
            date: $schedule->date,
            startTime: Carbon::createFromFormat('H:i:s', $schedule->time),
            endTime: Carbon::createFromFormat('H:i:s', $schedule->time)->addHour(),
            status: $schedule->status->value,
            learningMethod: $schedule->learning_method,
            address: $schedule->address,
            tutorName: $schedule->tutor?->user?->name,
            subjectName: $schedule->subject?->name,
            studentName: $schedule->student?->user?->name,
            tutorTelephoneNumber: $schedule->tutor?->user?->telephone_number,
            studentTelephoneNumber: $schedule->student?->user?->telephone_number
        );
    }

    public function cancelSchedule(int $scheduleId, string $reason): bool
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->status = ScheduleStatusEnum::CANCELLED->value;
        $schedule->reason = $reason;
        return $schedule->save();
    }

    public function createMeetingSchedule(array $data): void
    {
        Schedule::create([
            'tutor_id' => $data['tutor_id'],
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'status' => ScheduleStatusEnum::PENDING->value,
            'learning_method' => $data['learning_method'],
            'address' => $data['address'],
        ]);
    }

    public function getTutorSchedulesByDay(int $tutorId, ?string $day, int $paginate = 10): LengthAwarePaginator
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        
        $query = $tutor->tutorSchedules();

        if ($day !== null) {
            $query->where('day', $day);
        }

        return $query->paginate($paginate);
    }
}