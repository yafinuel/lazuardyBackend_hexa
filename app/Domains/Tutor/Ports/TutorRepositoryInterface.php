<?php

namespace App\Domains\Tutor\Ports;

use App\Domains\Tutor\Entities\TutorEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TutorRepositoryInterface
{
    public function getTutorById(int $tutorId): TutorEntity;
    public function update(int $tutorId, array $data): void;
    public function getByCriteria(array $filters, int $paginate): LengthAwarePaginator;
    public function createTutor(int $userId, array $data): int;
}