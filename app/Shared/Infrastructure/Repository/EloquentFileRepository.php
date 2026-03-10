<?php

namespace App\Shared\Infrastructure\Repository;

use App\Models\File;
use App\Shared\Ports\FileRepositoryInterface;

class EloquentFileRepository implements FileRepositoryInterface
{
    public function update(int $id, array $data)
    {
        File::where('id', $id)->update($data);
    }
}
