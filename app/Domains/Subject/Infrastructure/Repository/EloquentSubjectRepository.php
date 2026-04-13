<?php

namespace App\Domains\Subject\Infrastructure\Repository;

use App\Domains\Subject\Ports\SubjectRepositoryInterface;
use App\Models\Subject;
use App\Shared\Ports\FileStorageInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{
    public function __construct(protected FileStorageInterface $storage) {}
    
    public function getAllSubjects(): LengthAwarePaginator
    {
        return Subject::with('class')->paginate(15);
    }

    public function getSubjectByClass(int $classId): LengthAwarePaginator
    {
        return Subject::with('class')->where('class_id', $classId)->paginate(15);
    }

    public function getUniqueSubjectByLevel(string $level): LengthAwarePaginator
    {
        $uniqueSubjectIds = Subject::query()
            ->whereHas('class', function ($query) use ($level) {
                $query->where('level', $level);
            })
            ->selectRaw('MIN(id) as id')
            ->groupBy('name');

        return Subject::with('class')
            ->whereIn('id', $uniqueSubjectIds)
            ->orderBy('name')
            ->paginate(15);
    }
}
