<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\DayEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetTutorSchedulesByDayAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $tutorId, ?string $day, int $paginate = 10): LengthAwarePaginator
    {
        $day = $day ? DayEnum::tryFromTranslateName($day)?->value : null;
        return $this->repository->getTutorSchedulesByDay($tutorId, $day, $paginate);
    }
}