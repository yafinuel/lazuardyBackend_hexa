<?php

namespace App\Domains\UserProfile\User\Infrastructure\Job;

use App\Domains\UserProfile\User\Ports\UserRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPhotoProfileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $userId, 
        protected string $tempPath,
        protected string $folder
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FileStorageInterface $storage, UserRepositoryInterface $repository): void
    {
        $permanentPath = $storage->moveToPermanent($this->tempPath, $this->folder);
        
        $repository->updateRawProfilePhoto($this->userId, $permanentPath);

        $storage->delete($this->tempPath, 'local');
    }
}
