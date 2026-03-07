<?php

namespace App\Domains\Authentication\Ports;

use App\Domains\Authentication\Entities\OtpEntity;
use Carbon\Carbon;

    interface OtpRepositoryInterface
    {
        public function getOtp(string $identifier, string $identifierType, string $verificationType): ?OtpEntity;
        public function storeOtp(string $identifier, string $identifierType, string $code, string $verificationType, Carbon $expiredAt): ?OtpEntity;
        public function verifyOtp(string $identifier, string $identifierType, string $code): void;
        public function markAsUsed(OtpEntity $otpEntity): ?OtpEntity;
        public function incrementAttempts(OtpEntity $otpEntity): ?OtpEntity;
    }
