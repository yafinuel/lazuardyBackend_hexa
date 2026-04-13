<?php

namespace App\Domains\Package\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageRepositoryInterface
{
    public function getPackages(): LengthAwarePaginator;
}
