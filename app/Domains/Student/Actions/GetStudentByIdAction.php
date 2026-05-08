<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\Student\Ports\StudentServicePort;
use App\Shared\Enums\RoleEnum;

class GetStudentByIdAction
{
    public function __construct(protected StudentRepositoryInterface $repository, protected FileStorageInterface $storage, protected StudentServicePort $service) {}

    public function execute(int $userId): StudentEntity
    {
        $user = $this->service->userBiodata($userId);

        if($user->role == RoleEnum::PARENT){
            $parent = $this->service->parentBiodata($userId);
            $userId = $parent->studentId;
        }

        $student = $this->repository->getStudentById($userId);

        $student->profilePhotoUrl = $this->storage->getMedia($student->profilePhotoUrl);
        
        return $student;
    }
}