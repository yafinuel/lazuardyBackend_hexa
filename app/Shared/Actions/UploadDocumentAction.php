<?php

namespace App\Shared\Actions;

use App\Shared\Ports\FileStorageInterface;

class UploadDocumentAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FileStorageInterface $storage)
    {}

    public function execute($file, string $folder)
    {
        // $tempPath = $this->storage->uploadToTemp($file);
        // $file;
        // return;
    }
}
