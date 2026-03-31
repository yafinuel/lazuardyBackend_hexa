<?php

namespace App\Domains\Package\Ports;

interface PackageRepositoryInterface
{
    public function getPackages(): array;
}
