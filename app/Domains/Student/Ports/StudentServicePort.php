<?php

namespace App\Domains\Student\Ports;

interface StudentServicePort
{
    public function updateUser(int $userId, array $data): void;
}