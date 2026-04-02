<?php

namespace App\Domains\Subject\Actions;

use App\Domains\Subject\Ports\SubjectRepositoryInterface;

class GetSubjectByClassAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected SubjectRepositoryInterface $repository) {}

    public function execute(int $classId): array
    {
        return $this->repository->getSubjectByClass($classId);
    }
}
