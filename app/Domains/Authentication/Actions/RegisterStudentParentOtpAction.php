<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

class RegisterStudentParentOtpAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        $this->service->registerOtpEmail($data['email'], OtpIdentifierEnum::EMAIL, OtpVerificationTypeEnum::VERIFY_PARENT, "Kode Verifikasi OTP Lazuardy App", "Verifikasi Orang tua dan anak");
    }
}