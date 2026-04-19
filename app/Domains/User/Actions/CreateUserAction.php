<?php

namespace App\Domains\User\Actions;

use App\Domains\FileManager\Infrastructure\Storages\LaravelFileStorage;
use App\Domains\User\Ports\UserRepositoryInterface;
use Carbon\Carbon;

class CreateUserAction
{
    public function __construct(protected UserRepositoryInterface $repository, protected LaravelFileStorage $fileStorage) {}

    public function execute(array $data): int
    {
        $filter = [
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => $data['email_verified_at'] ?? null,
            'password' => $data['password'],
            'google_id' => $data['google_id'] ?? null,
            'facebook_id' => $data['facebook_id'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'],
            'telephone_number' => $data['telephone_number'],
            'home_address' => $data['home_address'] ?? null,
            'role' => $data['role'],
        ];

        if(isset($data['profile_photo'])) {
            $tempPath = $this->fileStorage->uploadToTemp($data['profile_photo']);
            $filter['profile_photo'] = $tempPath;
        }
        return $this->repository->createUser($filter);
    }
}