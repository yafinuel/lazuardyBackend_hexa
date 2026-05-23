<?php

namespace App\Domains\Schedule\Infrastructure\Repository;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Models\Schedule;
use App\Models\ScheduleTutor;
use App\Models\Tutor;
use App\Shared\Enums\ScheduleStatusEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentScheduleRepository implements ScheduleRepositoryInterface
{
    public function createTutorAvailabilitySchedule(int $tutorId, array $data): bool
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        $tutor->tutorSchedules()->createMany($data);
        return true;
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
            status: $schedule->status,
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
        return $schedule->update([
            'status' => ScheduleStatusEnum::CANCELLED,
            'reason' => $reason
        ]);
    }

    public function createMeetingSchedule(array $data): void
    {
        Schedule::create([
            'tutor_id' => $data['tutor_id'],
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'status' => ScheduleStatusEnum::PENDING,
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

    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10): LengthAwarePaginator
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        
        return $tutor->schedules()
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->paginate($paginate);
    }

    public function getStudentCountThisMonthSchedulesByTutorId(int $tutorId): int
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        
        return $tutor->schedules()
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->distinct('student_id')
            ->count('student_id');
    }
    
    public function getSchedulesByUserId(int $userId, ?array $filters, int $paginate = 10): LengthAwarePaginator
    {
        $query = Schedule::where(function ($q) use ($userId) {
            $q->where('tutor_id', $userId)->orWhere('student_id', $userId);
        });
        return $query
            ->when($filters['tutor_id'] ?? null, function ($q, $tutorId) {
                if (is_array($tutorId)) {
                    return $q->whereIn('tutor_id', $tutorId);
                }
                return $q->where('tutor_id', $tutorId);
            })
            ->when($filters['student_id'] ?? null, function ($q, $studentId) {
                if (is_array($studentId)) {
                    return $q->whereIn('student_id', $studentId);
                }
                return $q->where('student_id', $studentId);
            })
            ->when($filters['subject_id'] ?? null, function ($q, $subjectId) {
                if (is_array($subjectId)) {
                    return $q->whereIn('subject_id', $subjectId);
                }
                return $q->where('subject_id', $subjectId);
            })
            ->when($filters['date'] ?? null, function ($q, $date) {
                return $q->whereDate('date', Carbon::parse($date)->toDateString());
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                if (is_array($status)) {
                    return $q->whereIn('status', $status);
                }
                return $q->where('status', $status);
            })
            ->when($filters['learning_method'] ?? null, function ($q, $method) {
                if (is_array($method)) {
                    return $q->whereIn('learning_method', $method);
                }
                return $q->where('learning_method', $method);
            })
            ->with(['tutor.user', 'student.user', 'subject'])
            ->paginate($paginate);
    }
    
    public function updateSchedule(int $scheduleId, array $data): bool
    {
        $schedule = Schedule::findOrFail($scheduleId);
        return $schedule->update($data);
    }

    public function updateTutorSchedule(int $tutorId, array $data): bool
    {
        return DB::transaction(function () use ($tutorId, $data) {
            $newItems = collect($data);

            $oldSchedules = ScheduleTutor::where('tutor_id', $tutorId)->get();

            foreach ($oldSchedules as $oldSchedule) {
                $exists = $newItems->contains(function ($item) use ($oldSchedule) {
                    return $item['day'] === $oldSchedule->day && $item['time'] === $oldSchedule->time;
                });

                if (!$exists) {
                    $oldSchedule->delete();
                }
            }

            ScheduleTutor::upsert(
                $newItems->map(fn($item) => [
                    'tutor_id' => $tutorId,
                    'day' => $item['day'],
                    'time' => $item['time']
                ])->toArray(),
                ['tutor_id', 'day', 'time'],
                ['day', 'time']
            );

            return true;
        });
    }
}