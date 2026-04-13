<?php

namespace App\Domains\Package\Infrastructure\Repository;

use App\Domains\Package\Ports\PackageRepositoryInterface;
use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPackageRepository implements PackageRepositoryInterface
{
    public function getPackages(): LengthAwarePaginator
    {
        return Package::latest()->paginate(10);
    }
}
