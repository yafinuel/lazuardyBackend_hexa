<?php

namespace App\Domains\Package\Ports;

use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageRepositoryInterface
{
    public function getPackages(): LengthAwarePaginator;
    public function getPackageById(int $id): ?Package;
}
