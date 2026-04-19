<?php

namespace App\Domains\QueueManager\Infrastructure\Queues;

use App\Domains\QueueManager\Ports\QueueTaskInterface;

class LaravelTaskQueue implements QueueTaskInterface
{
    public function dispatch($job): void
    {
        dispatch($job);
    }
}