<?php

namespace App\Domains\Schedule\Ports;

interface ScheduleRepositoryInterface
{
    public function createTutorSchedule(int $tutorId, array $data): bool;
}