<?php

namespace App\Domains\Subject\Actions;

use App\Domains\Subject\Entities\SubjectEntity;
use App\Domains\Subject\Ports\SubjectRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetUniqueSubjectByLevelAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected SubjectRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(string $level): LengthAwarePaginator
    {
        $result = $this->repository->getUniqueSubjectByLevel($level);
        return $result->through(function ($subject) {
            return new SubjectEntity(
                id: $subject->id,
                name: $subject->name,
                icon_image_url: $subject->icon_image_path ? $this->storage->getMedia($subject->icon_image_path) : null,
                classId: $subject->class->id,
                className: $subject->class->name,
                classLevel: $subject->class->level
            );
        });
    }
}
