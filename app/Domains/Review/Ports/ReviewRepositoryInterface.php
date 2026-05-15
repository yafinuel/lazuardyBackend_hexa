<?php

namespace App\Domains\Review\Ports;

use App\Domains\Review\Entities\ReviewEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    public function create(int $studentId, array $data);
    public function findWithFilters(array $filters, int $pagination = 10): LengthAwarePaginator;
    public function getAvgRatingForTutor(int $tutorId): float;
    public function findById(int $id): ReviewEntity;
    public function update(int $id, array $data);
    public function delete(int $id): bool;
}