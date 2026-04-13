<?php

namespace App\Domains\Package\Actions;

use App\Domains\Package\Entities\PackageEntity;
use App\Domains\Package\Ports\PackageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPackagesAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected PackageRepositoryInterface $repository) {}

    public function execute(): LengthAwarePaginator
    {
        $result = $this->repository->getPackages();
        
        return $result->through(function ($package) {
            return new PackageEntity(
                id: $package->id,
                name: $package->name,
                session: $package->session,
                price: (float) $package->price,
                discount: (float) $package->discount,
                description: $package->description,
                imagePath: $package->image_path,
            );
        });
    }
}
