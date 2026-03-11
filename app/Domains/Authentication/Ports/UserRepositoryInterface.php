<?php

namespace App\Domains\Authentication\Ports;

use App\Domains\Authentication\Entities\UserAuthEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserAuthEntity;
    public function getToken(int $id): string;
    public function createStudentData(array $data): UserAuthEntity;
    public function createTutorData(array $data): UserAuthEntity;
    public function resetPassword(string $email, string $password): void;
    public function updateSocialId(int $userId, string $provider, string $providerId): void;
}
