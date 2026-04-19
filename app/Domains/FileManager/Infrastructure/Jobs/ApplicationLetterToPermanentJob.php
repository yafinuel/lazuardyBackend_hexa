<?php

namespace App\Domains\FileManager\Infrastructure\Jobs;

use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Shared\Enums\FileStatusEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ApplicationLetterToPermanentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $documentId, 
        protected string $tempPath,
        protected string $folder,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(FileStorageInterface $storage, FileRepositoryInterface $repository): void
    {
        $permanentPath = $storage->moveToPermanent($this->tempPath, $this->folder);
        
        $repository->update($this->documentId, [
            'path' => $permanentPath,
            'status' => FileStatusEnum::FIXED_STORAGE,
        ]);

        $storage->delete($this->tempPath, 'local');
    }
}
