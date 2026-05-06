<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Ports\ReviewRepositoryInterface;

class ReviewFromStudentPageAction
{
    public function __construct(protected ReviewRepositoryInterface $repository, protected FindReviewWithFiltersAction $findReviewAction) {}

    public function execute(int $tutorId, array $data, int $pagination = 10)
    {
        $data['tutor_id'] = $tutorId;
        $avgRating = $this->repository->getAvgRatingForTutor($tutorId);
        $reviews = $this->findReviewAction->execute($data, $pagination);
        
        return [
            'avg_rating' => $avgRating,
            'reviews' => $reviews
        ];
    }
}