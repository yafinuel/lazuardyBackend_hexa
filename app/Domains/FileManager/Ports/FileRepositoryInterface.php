<?php

namespace App\Domains\FileManager\Ports;

use App\Shared\Enums\FileTypeEnum;
use Illuminate\Support\Collection;

interface FileRepositoryInterface
{
    public function createUserFile(int $userId, string $filePath, FileTypeEnum $fileType, string $fileName): int;
    public function getFileByUserId(int $userId): Collection;
    public function update(int $id, array $data);
    public function userPhotoProfileUpdate(int $id, array $data);
}