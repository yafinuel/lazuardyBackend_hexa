<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Ports\ReviewRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateReviewAction
{
    public function __construct(protected ReviewRepositoryInterface $repository) {}

    public function execute(int $studentId, int $reviewId, array $data)
    {
        $review = $this->repository->findById($reviewId);

        if ($review->studentId !== $studentId) {
            throw new AuthorizationException('Unauthorized to update this review');
        }

        $changeFields = [];

        if (isset($data['rate'])) {
            $changeFields['rate'] = $data['rate'];
        }
        if (isset($data['comment'])) {
            $changeFields['comment'] = $data['comment'];
        }

        $this->repository->update($reviewId, $data);
    }
}