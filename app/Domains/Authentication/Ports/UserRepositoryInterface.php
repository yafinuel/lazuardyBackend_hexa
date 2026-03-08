<?php

namespace App\Domains\Authentication\Ports;

use App\Domains\Authentication\Entities\UserAuthEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserAuthEntity;
    public function getToken(int $id): string;
    public function create(array $data): void;
    public function createStudentData(array $userData, array $tutorData): void;
    public function createTutorData(array $userData, array $tutorData): void;
    public function updateSocialId(int $userId, string $provider, string $providerId): void;
}
