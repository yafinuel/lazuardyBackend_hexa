<?php

namespace App\Shared\Infrastructure\Queues;

use App\Shared\Ports\FileRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessFileUploadJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        protected int $documentId, 
        protected string $tempPath,
        protected string $folder,
    ) {}

    public function handle(FileStorageInterface $storage, FileRepositoryInterface $repository)
    {
        $permanentPath = $storage->moveToPermanent($this->tempPath, $this->folder);
        
        $repository->update($this->documentId, [
            'path' => $permanentPath,
            'status' => 'success'
        ]);

        $storage->delete($this->tempPath, 'local');
    }
}
