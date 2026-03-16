<?php

namespace App\Domains\UserProfile\Tutor\Actions;

use App\Domains\UserProfile\Tutor\Ports\TutorRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;

class TutorBiodataAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TutorRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $tutorId)
    {
        $tutor = $this->repository->getTutorBiodata($tutorId);
        $tutor->profilePhotoUrl = $this->storage->getMedia($tutor->profilePhotoUrl);

        return $tutor;
    }
}
