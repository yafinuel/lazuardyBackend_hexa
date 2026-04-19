<?php

namespace App\Domains\FileManager\Actions;

use App\Domains\FileManager\Infrastructure\Jobs\ApplicationLetterToPermanentJob;
use App\Domains\FileManager\Infrastructure\Jobs\ProfilePhotoToPermanentJob;
use App\Domains\QueueManager\Infrastructure\Queues\LaravelTaskQueue;

class MoveToPermanentPathAction
{
    public function __construct(protected LaravelTaskQueue $queue) {}

    public function execute(int $fileId, string $tempPath, string $folderPath, bool $isProfilePhoto = false)
    {
        if ($isProfilePhoto) {
            return $this->queue->dispatch(new ProfilePhotoToPermanentJob($fileId, $tempPath, $folderPath));
        } else{
            return $this->queue->dispatch(new ApplicationLetterToPermanentJob($fileId, $tempPath, $folderPath));
        }
    }
}