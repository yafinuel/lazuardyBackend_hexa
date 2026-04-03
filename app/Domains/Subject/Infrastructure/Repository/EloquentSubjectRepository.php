<?php

namespace App\Domains\Subject\Infrastructure\Repository;

use App\Domains\Subject\Entities\SubjectEntity;
use App\Domains\Subject\Ports\SubjectRepositoryInterface;
use App\Models\Subject;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{
    public function getAllSubjects(): array
    {
        $paginator = Subject::with('class')->paginate(15);

        $subjects = collect($paginator->items())->map(function (Subject $subject) {
            return new SubjectEntity(
                id: $subject->id,
                name: $subject->name,
                icon_image_path: $subject->icon_image_path,
                classId: $subject->class->id,
                className: $subject->class->name,
                classLevel: $subject->class->level
            );
        })->toArray();

        return [
            'data' => $subjects,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getSubjectByClass(int $classId): array
    {
        $paginator = Subject::with('class')
            ->where('class_id', $classId)
            ->paginate(15);

        $subjects = collect($paginator->items())->map(function (Subject $subject) {
            return new SubjectEntity(
                id: $subject->id,
                name: $subject->name,
                icon_image_path: $subject->icon_image_path,
                classId: $subject->class->id,
                className: $subject->class->name,
                classLevel: $subject->class->level
            );
        })->toArray();

        return [
            'data' => $subjects,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getUniqueSubjectByLevel(string $level): array
    {
        $uniqueSubjectIds = Subject::query()
            ->whereHas('class', function ($query) use ($level) {
                $query->where('level', $level);
            })
            ->selectRaw('MIN(id) as id')
            ->groupBy('name');

        $paginator = Subject::with('class')
            ->whereIn('id', $uniqueSubjectIds)
            ->orderBy('name')
            ->paginate(15);

        $subjects = collect($paginator->items())->map(function (Subject $subject) {
            return [
                'name' => $subject->name,
                'icon_image_path' => $subject->icon_image_path,
            ];
        });

        return [
            'data' => $subjects,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
}
