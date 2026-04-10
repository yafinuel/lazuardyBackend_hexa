<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;

class GetStudentByIdAction
{
    public function __construct(protected StudentRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $studentId): StudentEntity
    {
        $student = $this->repository->getStudentById($studentId);

        $student->profilePhotoUrl = $this->storage->getMedia($student->profilePhotoUrl);
        
        return $student;
    }
}