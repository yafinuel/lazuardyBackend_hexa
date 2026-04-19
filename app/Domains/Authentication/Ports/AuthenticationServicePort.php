<?php

namespace App\Domains\Authentication\Ports;

interface AuthenticationServicePort
{
    public function tutorRegister(array $userData, array $tutorData, int $subjectData, array $scheduleData, array $fileData): int;
    public function studentRegister(array $userData, array $studentData): int;
    public function getToken(int $userId): string;
}