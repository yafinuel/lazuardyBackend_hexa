<?php

namespace App\Domains\FileManager\Actions;

use App\Domains\FileManager\Infrastructure\Jobs\ApplicationLetterToPermanentJob;
use App\Domains\QueueManager\Infrastructure\Queues\LaravelTaskQueue;

class MoveToPermanentPathAction
{
    public function __construct(protected LaravelTaskQueue $queue) {}

    public function execute($fileId, $tempPath, $folderPath)
    {
        return $this->queue->dispatch(new ApplicationLetterToPermanentJob($fileId, $tempPath, $folderPath));
    }
}