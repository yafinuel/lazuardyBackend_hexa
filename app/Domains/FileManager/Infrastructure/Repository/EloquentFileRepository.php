<?php

namespace App\Domains\FileManager\Infrastructure\Repository;

use App\Domains\FileManager\Entities\FileEntity;
use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Models\File;
use App\Models\User;
use App\Shared\Enums\FileStatusEnum;
use App\Shared\Enums\FileTypeEnum;
use Illuminate\Support\Collection;

class EloquentFileRepository implements FileRepositoryInterface
{
    public function createUserFile(int $userId, string $filePath, FileTypeEnum $fileType, string $fileName): int
    {
        $user = User::where('id', $userId)->firstOrFail();
        $file = $user->files()->create([
            'name' => $fileName,
            'type' => $fileType,
            'path' => $filePath,
            'status' => FileStatusEnum::TEMPORARY_STORAGE,
        ]);
        return $file->id;
    }
    
    public function getFileByUserId(int $userId): Collection
    {
        $files = File::where('user_id', $userId)->get();

        return $files->map(function ($file) {
            return new FileEntity(
                id: $file->id,
                userId: $file->user_id,
                name: $file->name,
                type: $file->type->displayName(),
                url: $file->path,
                status: $file->status->value,
            );
        });
    }

    public function update(int $id, array $data)
    {
        File::where('id', $id)->update($data);
    }

    public function userPhotoProfileUpdate(int $id, array $data)
    {
        User::where('id', $id)->update($data);
    }
}