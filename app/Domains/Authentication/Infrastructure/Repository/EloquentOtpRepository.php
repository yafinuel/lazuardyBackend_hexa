<?php

namespace App\Domains\Authentication\Infrastructure\Repository;

use App\Domains\Authentication\Entities\OtpEntity;
use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use App\Models\Otp as OtpEloquent;
use Carbon\Carbon;

class EloquentOtpRepository implements OtpRepositoryInterface
{
    
    public function getOtp(string $identifier, string $identifierType, string $verificationType): ?OtpEntity
    {
        $otp = OtpEloquent::byIdentifier($identifier, $identifierType)
                ->byVerificationType($verificationType)
                ->valid()
                ->latest()
                ->first();

        if(!$otp) return null;

        return new OtpEntity(
            id: $otp->id,
            identifier: $otp->identifier,
            identifierType: $otp->identifierType,
            verificationType: $otp->verificationType,
            code: $otp->code,
            attempts: $otp->attempts,
            is_used: $otp->is_used,
            expiredAt: $otp->expiredAt,
        );
    }

    public function storeOtp(string $identifier, string $identifierType, string $code, string $verificationType, Carbon $expiredAt): OtpEntity
    {
        $otp = OtpEloquent::create([
            "identifier" => $identifier,
            "identifier_type" => $identifierType,
            "code" => hash('sha256', $code),
            "verification_type" => $verificationType,
            "expired_at" => $expiredAt
        ]);

        return new OtpEntity(
            id: $otp->id,
            identifier: $otp->identifier,
            identifierType: $otp->identifierType,
            verificationType: $otp->verificationType,
            code: $otp->code,
            attempts: $otp->attempts,
            is_used: $otp->is_used,
            expiredAt: $otp->expiredAt,
        );
    }

    public function markAsUsed(OtpEntity $otpEntity): ?OtpEntity
    {
        $otpEntity->is_used = true;

        $otpEloquent = OtpEloquent::findOrFail($otpEntity->id);
        $otpEloquent->is_used = $otpEntity->is_used;
        $otpEloquent->save();

        return $otpEntity;
    }

    public function incrementAttempts(OtpEntity $otpEntity): ?OtpEntity
    {
        $otpEntity->attempts += 1;
        
        $otpEloquent = OtpEloquent::findOrFail($otpEntity->id);
        $otpEloquent->attempts = $otpEntity->attempts;
        $otpEloquent->save();

        return $otpEntity;
    }
}
