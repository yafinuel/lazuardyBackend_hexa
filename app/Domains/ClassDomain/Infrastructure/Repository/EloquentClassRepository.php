<?php

namespace App\Domains\ClassDomain\Infrastructure\Repository;

use App\Domains\ClassDomain\Entities\ClassEntity;
use App\Domains\ClassDomain\Ports\ClassRepositoryInterface;
use App\Models\ClassModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentClassRepository implements ClassRepositoryInterface
{
    public function getClassLevels(): Collection
    {
        return ClassModel::distinct()->pluck('level');
    }

    public function getAllClasses(): LengthAwarePaginator
    {
        return ClassModel::paginate(10);

        // $classes = collect($paginator->items())->map(function (ClassModel $class) {
        //     return new ClassEntity(
        //         id: $class->id,
        //         name: $class->name,
        //         level: $class->level
        //     );
        // })->toArray();
    }

    public function getClassesByLevel(string $level): array
    {
        $classes = ClassModel::where('level', $level)->get();

        return $classes->map(function (ClassModel $class) {
            return new ClassEntity(
                id: $class->id,
                name: $class->name,
                level: $class->level
            );
        })->toArray();
    }
}
