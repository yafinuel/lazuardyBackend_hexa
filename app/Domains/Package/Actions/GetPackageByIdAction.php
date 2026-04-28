<?php

namespace App\Domains\Package\Actions;

use App\Domains\Package\Ports\PackageRepositoryInterface;

class GetPackageByIdAction
{
    public function __construct(
        protected PackageRepositoryInterface $repository
    )
    {
    }

    public function execute(int $packageId)
    {
        return $this->repository->getPackageById($packageId);
    }
}