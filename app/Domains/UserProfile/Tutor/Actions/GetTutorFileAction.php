<?php

namespace App\Domains\UserProfile\Tutor\Actions;

use App\Domains\UserProfile\Tutor\Ports\TutorRepositoryInterface;
use App\Shared\Ports\FileRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;

class GetTutorFileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FileRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $tutorId)
    {
        $files = $this->repository->getFileByUserId($tutorId);

        if($files->isEmpty()) {
            return null;
        }

        $files->each(function ($file) {
            $fileUrl = $this->storage->getMedia($file->url);
            $file->url = $fileUrl;
        });
        
        return $files;
    }
}
