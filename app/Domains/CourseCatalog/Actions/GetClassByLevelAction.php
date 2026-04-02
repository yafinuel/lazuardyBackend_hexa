<?php

namespace App\Domains\CourseCatalog\Actions;

use App\Domains\CourseCatalog\Ports\ClassRepositoryInterface;

class GetClassByLevelAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ClassRepositoryInterface $repository) {}

    public function execute(string $level)
    {
        // Implement the logic to get classes by level using the repository
        // For example:
        return $this->repository->getClassesByLevel($level);
        // return []; // Placeholder return value
    }
}
