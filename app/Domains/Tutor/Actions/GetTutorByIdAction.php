<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;

class GetTutorByIdAction
{
    public function __construct(protected TutorRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $tutorId)
    {
        $tutor = $this->repository->getTutorById($tutorId);
        $tutor->profilePhotoUrl = $this->storage->getMedia($tutor->profilePhotoUrl);

        return $tutor;
    }
}