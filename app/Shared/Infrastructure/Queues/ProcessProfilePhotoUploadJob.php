<?php

namespace App\Shared\Infrastructure\Queues;

use App\Shared\Ports\FileRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessProfilePhotoUploadJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $userId, 
        protected string $tempPath,
        protected string $folder,)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FileStorageInterface $storage, FileRepositoryInterface $repository): void
    {
        $permanentPath = $storage->moveToPermanent($this->tempPath, $this->folder);
        
        $repository->userPhotoProfileUpdate($this->userId, [
            'profile_photo_path' => $permanentPath,
        ]);

        $storage->delete($this->tempPath, 'local');
    }
}
