<?php

namespace App\Domains\OtpManager\Infrastructure\Repository;

use App\Domains\OtpManager\Entities\OtpEntity;
use App\Domains\OtpManager\Ports\OtpRepositoryInterface;
use App\Models\Otp;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Carbon\Carbon;

class EloquentOtpRepository implements OtpRepositoryInterface
{
    
    public function storeOtp(string $identifier, OtpIdentifierEnum $identifierType, string $code, OtpVerificationTypeEnum $verificationType, Carbon $expiredAt): OtpEntity
    {
        $otp = Otp::create([
            "identifier" => $identifier,
            "identifier_type" => $identifierType,
            "code" => hash('sha256', $code),
            "verification_type" => $verificationType,
            "expired_at" => $expiredAt
        ]);

        return new OtpEntity(
            id: $otp->id,
            identifier: $otp->identifier,
            identifierType: $otp->identifier_type,
            verificationType: $otp->verification_type,
            code: $otp->code,
            attempts: $otp->attempts,
            is_used: $otp->is_used,
            expiredAt: $otp->expired_at
        );
    }

    public function getOtp(string $identifier, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType): ?OtpEntity
    {
        $otp = Otp::where('identifier', $identifier)
                    ->where('identifier_type', $identifierType)
                    ->where('verification_type', $verificationType)
                    ->where('is_used', false)
                    ->where('expired_at', '>', Carbon::now())
                    ->latest()
                    ->first();

        if (!$otp) {
            return null;
        }

        return new OtpEntity(
            id: $otp->id,
            identifier: $otp->identifier,
            identifierType: $otp->identifier_type,
            verificationType: $otp->verification_type,
            code: $otp->code,
            attempts: $otp->attempts,
            is_used: $otp->is_used,
            expiredAt: $otp->expired_at
        );
    }

    

    public function markAsUsed(OtpEntity $otpEntity): bool
    {
        $otpEntity->is_used = true;

        $otpEloquent = Otp::findOrFail($otpEntity->id);
        $otpEloquent->is_used = $otpEntity->is_used;
        $otpEloquent->save();

        return true;
    }

    public function incrementAttempts(OtpEntity $otpEntity): bool
    {
        $otpEntity->attempts += 1;
        
        $otpEloquent = Otp::findOrFail($otpEntity->id);
        $otpEloquent->attempts = $otpEntity->attempts;
        $otpEloquent->save();

        return true;
    }
}