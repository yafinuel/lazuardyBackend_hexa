<?php

namespace App\Shared\Infrastructure\Queues;

use App\Shared\Ports\TaskQueueInterface;

class LaravelTaskQueue implements TaskQueueInterface
{
    public function dispatch($job): void
    {
        dispatch($job);
    }
}
