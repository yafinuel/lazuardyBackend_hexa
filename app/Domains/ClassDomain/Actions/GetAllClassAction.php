<?php

namespace App\Domains\ClassDomain\Actions;

use App\Domains\ClassDomain\Ports\ClassRepositoryInterface;

class GetAllClassAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ClassRepositoryInterface $repository) {}

    public function execute()
    {
        return $this->repository->getAllClasses();
    }
}
