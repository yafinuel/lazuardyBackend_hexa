<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;

class GetTutorByCriteria
{
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(array $data = [], int $paginate = 10)
    {
        return $this->repository->getByCriteria($data, $paginate);
    }
}