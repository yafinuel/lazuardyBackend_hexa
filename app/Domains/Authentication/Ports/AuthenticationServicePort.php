<?php

namespace App\Domains\Authentication\Ports;

use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

interface AuthenticationServicePort
{
    public function tutorRegister(array $userData, array $tutorData, int $subjectData, array $scheduleData, array $fileData): int;
    public function studentRegister(array $userData, array $studentData): int;
    public function getToken(int $userId): string;
    public function registerOtpEmail(string $email, OtpIdentifierEnum $otpIdentifierEnum, OtpVerificationTypeEnum $otpTypeEnum, string $subject, string $title): string;
    public function verifyOtpEmail(string $code, string $identifier, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType): ?string;
    public function resetPassword(string $email, string $resetToken, string $newPassword): void;
}