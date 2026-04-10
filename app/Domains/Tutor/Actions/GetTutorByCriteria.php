<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;

class GetTutorByCriteria
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(array $data = [], int $paginate = 10): array
    {
        return $this->repository->getByCriteria($data, $paginate);
    }
}