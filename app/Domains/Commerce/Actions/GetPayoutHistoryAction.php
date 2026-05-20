<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPayoutHistoryAction
{
    public function __construct(
        protected CommerceRepositoryInterface $repository,
    ) {}

    public function execute(int $tutorId, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getPayoutByTutorId($tutorId, $filters, $perPage);
    }
}