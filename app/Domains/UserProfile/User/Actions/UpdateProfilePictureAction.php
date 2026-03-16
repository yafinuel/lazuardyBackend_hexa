<?php

namespace App\Domains\UserProfile\User\Actions;

use App\Domains\UserProfile\User\Ports\UserRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
use App\Shared\Ports\TaskQueueInterface;

class UpdateProfilePictureAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository, protected FileStorageInterface $storage, protected TaskQueueInterface $queue) {}

    public function execute(int $userId, array $data)
    {
        $data['profile_picture_url'] = $this->storage->uploadToTemp($data['profile_picture']);
        $data['profile_picture_name'] = basename($data['profile_picture_url']);

        $this->repository->updateProfilePhoto($userId, $data);
    }
}
