<?php

namespace App\Domains\ClassDomain\Infrastructure\Repository;

use App\Domains\ClassDomain\Entities\ClassEntity;
use App\Domains\ClassDomain\Ports\ClassRepositoryInterface;
use App\Models\ClassModel;
use Illuminate\Support\Collection;

class EloquentClassRepository implements ClassRepositoryInterface
{
    public function getClassLevels(): Collection
    {
        return ClassModel::distinct()->pluck('level');
    }

    public function getAllClasses(): array
    {
        $paginator = ClassModel::paginate(10);

        $classes = collect($paginator->items())->map(function (ClassModel $class) {
            return new ClassEntity(
                id: $class->id,
                name: $class->name,
                level: $class->level
            );
        })->toArray();

        return [
            'data' => $classes,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
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
