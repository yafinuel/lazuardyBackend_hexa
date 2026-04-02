<?php

namespace App\Domains\CourseCatalog\Infrastructure\Repository;

use App\Domains\CourseCatalog\Ports\ClassRepositoryInterface;
use App\Models\ClassModel;
use Illuminate\Support\Collection;

class EloquentClassRepository implements ClassRepositoryInterface
{
    public function getClassLevels(): Collection
    {
        return ClassModel::distinct()->pluck('level');
    }
}
