<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;

class GetStudentCountThisMonthSchedulesByTutorId
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $tutorId): int
    {
        return $this->repository->getStudentCountThisMonthSchedulesByTutorId($tutorId);
    }
}