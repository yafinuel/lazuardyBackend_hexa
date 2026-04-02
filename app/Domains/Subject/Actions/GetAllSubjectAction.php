<?php

namespace App\Domains\Subject\Actions;

use App\Domains\Subject\Ports\SubjectRepositoryInterface;

class GetAllSubjectAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected SubjectRepositoryInterface $repository) {}

    public function execute(): array
    {
        return $this->repository->getAllSubjects();
    }
}
