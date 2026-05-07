<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetFilteredSchedulesByStudentId
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $studentId, ?array $filters, int $paginate = 10): LengthAwarePaginator
    {
        return $this->repository->getFilteredSchedulesByStudentId($studentId, $filters, $paginate);
    }
}