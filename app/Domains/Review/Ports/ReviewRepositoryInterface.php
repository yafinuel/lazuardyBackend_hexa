<?php

namespace App\Domains\Review\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    public function create(int $studentId, array $data);
    public function findWithFilters(array $filters, int $pagination = 10): LengthAwarePaginator;
    public function getAvgRatingForTutor(int $tutorId): float;
}