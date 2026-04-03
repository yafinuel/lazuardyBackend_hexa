<?php

namespace App\Domains\Subject\Actions;

use App\Domains\Subject\Ports\SubjectRepositoryInterface;

class GetUniqueSubjectByLevelAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected SubjectRepositoryInterface $repository) {}

    public function execute(string $level): array
    {
        return $this->repository->getUniqueSubjectByLevel($level);
    }
}
