<?php

namespace App\Domains\Review\Entities;

use Carbon\Carbon;

class ReviewEntity
{
    public function __construct(
        public ?int $id,
        public int $studentId,
        public int $tutorId,
        public int $rate,
        public ?string $comment,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {}
}