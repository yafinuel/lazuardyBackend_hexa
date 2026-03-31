<?php

namespace App\Domains\Package\Infrastructure\Repository;

use App\Domains\Package\Entities\PackageEntity;
use App\Domains\Package\Ports\PackageRepositoryInterface;
use App\Models\Package;

class EloquentPackageRepository implements PackageRepositoryInterface
{
    public function getPackages(): array
    {
        $paginator = Package::latest()->paginate(10);

        $packages = collect($paginator->items())->map(function (Package $package) {
            return new PackageEntity(
                id: $package->id,
                name: $package->name,
                session: $package->session,
                price: (float) $package->price,
                discount: (float) $package->discount,
                description: $package->description,
                imagePath: $package->image_path,
            );
        })->toArray();

        return [
            'data' => $packages,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
}
