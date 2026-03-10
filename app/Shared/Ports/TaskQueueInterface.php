<?php

namespace App\Shared\Ports;

interface TaskQueueInterface
{
    public function dispatch($job): void;
}
