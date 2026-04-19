<?php

namespace App\Domains\OtpManager\Ports;

use App\Domains\OtpManager\Entities\OtpEntity;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Carbon\Carbon;

interface OtpRepositoryInterface
{
    public function storeOtp(string $identifier, OtpIdentifierEnum $identifierType, string $code, OtpVerificationTypeEnum $verificationType, Carbon $expiredAt): OtpEntity;
    public function getOtp(string $identifier, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType): ?OtpEntity;
    public function incrementAttempts(OtpEntity $otp): bool;
    public function markAsUsed(OtpEntity $otp): bool;
}