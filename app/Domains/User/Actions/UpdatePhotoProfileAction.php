<?php

namespace App\Domains\User\Actions;

use App\Domains\FileManager\Actions\MoveToPermanentPathAction;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\User\Ports\UserRepositoryInterface;

class UpdatePhotoProfileAction
{
    public function __construct(
        protected UserRepositoryInterface $repository, 
        protected FileStorageInterface $storage,
        protected MoveToPermanentPathAction $moveToPermanentPathAction,
    ) {}

    public function execute(int $userId, array $data): void
    {
        $tempPath = $this->storage->uploadToTemp($data['profile_photo']);
        $userData = ['profile_photo_path' => $tempPath];

        $this->repository->update($userId, $userData);

        $this->moveToPermanentPathAction->execute($userId, $tempPath, 'profile_photos/' . $userId, true);
    }
}