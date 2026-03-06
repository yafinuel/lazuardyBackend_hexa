<?php

namespace App\Domains\Authentication\Ports;

use App\Domains\Authentication\Models\Otp;
use Carbon\Carbon;

interface OtpRepositoryInterface
{
    public function storeOtp(string $identifier, string $identifierType, string $code, string $verificationType, Carbon $expiredAt): Otp;
    // public function verifyOtp(string $identifier, string $identifierType, string $code): void;
}
