<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Entities\ReviewEntity;
use App\Domains\Review\Ports\ReviewRepositoryInterface;

class FindReviewWithFiltersAction
{
    public function __construct(protected ReviewRepositoryInterface $repository) {}

    public function execute(array $filters, int $pagination = 10)
    {
        $schedules = $this->repository->findWithFilters($filters, $pagination);
        return $schedules->through(function ($schedule) {
            return new ReviewEntity(
                id: $schedule->id,
                studentId: $schedule->student_id,
                tutorId: $schedule->tutor_id,
                rate: $schedule->rate,
                comment: $schedule->comment,
                createdAt: $schedule->created_at,
                updatedAt: $schedule->updated_at,
            );
        });
    }
}