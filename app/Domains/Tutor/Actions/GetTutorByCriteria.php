<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;

class GetTutorByCriteria
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(array $data): array
    {
        return $this->repository->getByCriteria($data);
    }
}