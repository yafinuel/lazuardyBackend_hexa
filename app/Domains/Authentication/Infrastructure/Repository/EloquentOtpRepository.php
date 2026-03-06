<?php

namespace App\Domains\Authentication\Infrastructure\Repository;

use App\Domains\Authentication\Models\Otp;
use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use Carbon\Carbon;

class EloquentOtpRepository implements OtpRepositoryInterface
{
    public function storeOtp(string $identifier, string $identifierType, string $code, string $verificationType, Carbon $expiredAt): Otp
    {
        return Otp::create([
            "identifier" => $identifier,
            "identifier_type" => $identifierType,
            "code" => hash('sha256', $code),
            "verification_type" => $verificationType,
            "expired_at" => $expiredAt
        ]);
    }
}
