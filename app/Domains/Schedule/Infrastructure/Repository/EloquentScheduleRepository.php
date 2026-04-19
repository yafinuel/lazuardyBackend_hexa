<?php

namespace App\Domains\Schedule\Infrastructure\Repository;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Models\Tutor;

class EloquentScheduleRepository implements ScheduleRepositoryInterface
{
    public function createTutorSchedule(int $tutorId, array $data): bool
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();
        $tutor->schedules()->createMany($data);
        return true;
    }
}