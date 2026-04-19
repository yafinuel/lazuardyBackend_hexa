<?php

namespace App\Domains\FileManager\Infrastructure\Jobs;

use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\User\Ports\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProfilePhotoToPermanentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $userId,
        protected string $tempPath,
        protected string $folderPath,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(FileStorageInterface $storage, UserRepositoryInterface $userRepository): void
    {
        $permanentPath = $storage->moveToPermanent($this->tempPath, $this->folderPath);

        $userRepository->update($this->userId, [
            'profile_photo_path' => $permanentPath,
        ]);

        $storage->delete($this->tempPath, 'local');
    }
}
