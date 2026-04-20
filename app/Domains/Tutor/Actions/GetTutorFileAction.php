<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;

class GetTutorFileAction
{
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