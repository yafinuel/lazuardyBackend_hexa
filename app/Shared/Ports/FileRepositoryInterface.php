<?php

namespace App\Shared\Ports;

interface FileRepositoryInterface
{
    public function update(int $id, array $data);
}
