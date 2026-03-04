<?php

namespace App\Domains\Authentication\Infrastructure;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function updateSocialId(int $userId, string $provider, string $providerId): void
    {
        $provider = $provider . "_id";
        User::where('id', $userId)->update([
            $provider => $providerId
        ]);
    }
}
