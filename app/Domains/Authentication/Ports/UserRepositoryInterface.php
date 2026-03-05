<?php

namespace App\Domains\Authentication\Ports;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function createStudentData(array $userData, array $tutorData): User;
    public function createTutorData(array $userData, array $tutorData): User;
    public function updateSocialId(int $userId, string $provider, string $providerId): void;
}
