<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

class VerifyStudentParentOtpAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        return $this->service->verifyOtpEmail($data['otp'], $data['email'], OtpIdentifierEnum::EMAIL, OtpVerificationTypeEnum::VERIFY_PARENT);
    }
}