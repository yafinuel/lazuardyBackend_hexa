<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use Exception;

class VerifyOtpAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected OtpRepositoryInterface $repository)
    {}

    public function execute(string $identifier, string $identifierType, string $verificationType, string $code)
    {
        $otp = $this->repository->getOtp($identifier, $identifierType, $verificationType);
        
        if(!$otp){
            throw new Exception("OTP tidak ditemukan atau sudah kedaluwarsa.", 404);
        }
        
        if(hash('sha256', $code) == $otp->code){
            $otp = $this->repository->markAsUsed($otp);
            
            return [
                'status' => 'success',
                'otp' => $otp,
            ];
        } else {
            $otp = $this->repository->incrementAttempts($otp);
            return [
                'status' => 'failed',
                'otp' => $otp,
            ];
        }
    }
}
