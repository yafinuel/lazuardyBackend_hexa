<?php

namespace App\Domains\CourseCatalog\Actions;

use App\Domains\CourseCatalog\Ports\ClassRepositoryInterface;

class GetClassLevelAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ClassRepositoryInterface $repository) {}

    public function execute()
    {
        return $this->repository->getClassLevels();
    }
}
