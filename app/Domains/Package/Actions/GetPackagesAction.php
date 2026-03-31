<?php

namespace App\Domains\Package\Actions;

use App\Domains\Package\Ports\PackageRepositoryInterface;

class GetPackagesAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected PackageRepositoryInterface $repository) {}

    public function execute(): array
    {
        return $this->repository->getPackages();
    }
}
