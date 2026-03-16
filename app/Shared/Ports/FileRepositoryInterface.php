<?php

namespace App\Shared\Ports;

use Illuminate\Support\Collection;

interface FileRepositoryInterface
{
    public function getFileByUserId(int $userId): Collection;
    public function update(int $id, array $data);
}
