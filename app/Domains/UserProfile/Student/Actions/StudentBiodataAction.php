<?php

namespace App\Domains\UserProfile\Student\Actions;

use App\Domains\UserProfile\Student\Entities\StudentEntity;
use App\Domains\UserProfile\Student\Ports\StudentRepositoryInterface;
use App\Shared\Infrastructure\Storage\LaravelFileStorage;
use App\Shared\Ports\FileStorageInterface;

class StudentBiodataAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected StudentRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $studentId): StudentEntity
    {
        $student = $this->repository->getStudentProfile($studentId);

        $student->profilePhotoUrl = $this->storage->getMedia($student->profilePhotoUrl);
        
        return $student;
    }
}
