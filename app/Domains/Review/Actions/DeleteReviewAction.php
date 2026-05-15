<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Ports\ReviewRepositoryInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class DeleteReviewAction
{
    public function __construct(protected ReviewRepositoryInterface $repository) {}

    public function execute(int $studentId, int $reviewId)
    {
        $review = $this->repository->findById($reviewId);

        if ($review->studentId !== $studentId) {
            throw new AuthorizationException('Unauthorized to delete this review');
        }

        $this->repository->delete($reviewId);
    }
}