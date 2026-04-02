<?php

namespace App\Domains\CourseCatalog\Ports;

use Illuminate\Support\Collection;

interface ClassRepositoryInterface
{
    public function getClassLevels(): Collection;
}
