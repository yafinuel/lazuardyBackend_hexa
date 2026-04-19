<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

class RegisterOtpEmailAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        return $this->service->registerOtpEmail($data['email'], OtpIdentifierEnum::EMAIL, OtpVerificationTypeEnum::REGISTER, "Kode Verifikasi OTP Lazuardy App", "Verifikasi Akun");
    }
}