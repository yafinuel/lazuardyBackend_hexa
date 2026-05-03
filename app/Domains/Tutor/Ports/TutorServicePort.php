<?php

namespace App\Domains\Tutor\Ports;

interface TutorServicePort
{
    public function updateUser(int $userId, array $data): void;
}