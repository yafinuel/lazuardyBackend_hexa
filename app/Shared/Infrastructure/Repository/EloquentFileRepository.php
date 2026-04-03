<?php

namespace App\Shared\Infrastructure\Repository;

use App\Models\File;
use App\Models\User;
use App\Shared\Entities\FileEntity;
use App\Shared\Ports\FileRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentFileRepository implements FileRepositoryInterface
{
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
                status: $file->status,
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
