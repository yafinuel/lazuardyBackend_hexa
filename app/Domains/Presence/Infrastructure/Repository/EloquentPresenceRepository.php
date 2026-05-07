<?php

namespace App\Domains\Presence\Infrastructure\Repository;

use App\Domains\Presence\Ports\PresenceRepositoryInterface;
use App\Models\Presence;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPresenceRepository implements PresenceRepositoryInterface
{
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

    public function getPresencesByUserId(int $userId, ?array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Presence::where(function ($q) use ($userId) {
            $q->where('student_id', $userId)->orWhere('tutor_id', $userId);
        })->with(['schedule', 'tutor', 'student']);

        return $query
            ->when(isset($filters['tutor_id']), function ($q) use ($filters) {
                if (is_array($filters['tutor_id'])) {
                    return $q->whereIn('tutor_id', $filters['tutor_id']);
                }
                return $q->where('tutor_id', $filters['tutor_id']);
            })
            ->when(isset($filters['student_id']), function ($q) use ($filters) {
                if (is_array($filters['student_id'])) {
                    return $q->whereIn('student_id', $filters['student_id']);
                }
                return $q->where('student_id', $filters['student_id']);
            })
            ->when(isset($filters['schedule_id']), function ($q) use ($filters) {
                if (is_array($filters['schedule_id'])) {
                    return $q->whereIn('schedule_id', $filters['schedule_id']);
                }
                return $q->where('schedule_id', $filters['schedule_id']);
            })
            ->when(isset($filters['topic']), function ($q) use ($filters) {
                return $q->where('topic', 'like', '%' . $filters['topic'] . '%');
            })
            ->paginate($perPage);
    }
}