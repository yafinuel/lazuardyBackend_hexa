<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerifyOtpAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected OtpRepositoryInterface $repository)
    {}

    public function execute(string $identifier, string $identifierType, string $verificationType, string $code): ?string
    {
        $otp = $this->repository->getOtp($identifier, $identifierType, $verificationType);
        
        if(!$otp){
            throw new Exception("OTP tidak ditemukan atau sudah kedaluwarsa.", 404);
        }
        
        if (hash('sha256', $code) !== $otp->code) {
            $this->repository->incrementAttempts($otp);
            throw new Exception("Kode OTP yang Anda masukkan salah.", 403);
        }

        $this->repository->markAsUsed($otp);

        $resetToken = null;

        if ($verificationType == OtpVerificationTypeEnum::FORGOT_PASSWORD->value) {
            $resetToken = Str::random(16);
            $cacheKey = "password_reset_token_" . $identifier;
            Cache::put($cacheKey, $resetToken, now()->addMinutes(5));
        }

        return $resetToken;
    }
}
