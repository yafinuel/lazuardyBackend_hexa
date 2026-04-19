<?php

namespace App\Domains\FileManager\Actions;

use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Shared\Enums\FileTypeEnum;
use Illuminate\Http\UploadedFile;

class SaveJobApplicationLetterAction
{
    public function __construct(protected FileRepositoryInterface $repository, protected FileStorageInterface $storage) {}

    public function execute(int $userId, UploadedFile $file, FileTypeEnum $fileType): array
    {
        // upload to temp
        $tempPath = $this->storage->uploadToTemp($file);
        $fileName = basename($tempPath);

        return [
            "id" => $this->repository->createUserFile($userId, $tempPath, $fileType, $fileName),
            "temp_path" => $tempPath,
            "file_name" => $fileName,
        ];
    }
}