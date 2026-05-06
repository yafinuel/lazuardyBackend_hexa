<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Ports\ReviewRepositoryInterface;

class CreateReviewAction
{
    public function __construct(protected ReviewRepositoryInterface $repository) {}

    public function execute(int $studentId ,array $data)
    {
        $toDb = [
            'tutor_id' => $data['tutor_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ];
        $this->repository->create($studentId, $toDb);
    }
}