<?php

namespace App\Domains\QueueManager\Ports;

interface QueueTaskInterface
{
    public function dispatch($job): void;
}