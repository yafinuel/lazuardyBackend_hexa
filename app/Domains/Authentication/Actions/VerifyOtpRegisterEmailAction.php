<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

class VerifyOtpRegisterEmailAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data): ?string
    {
        return $this->service->verifyOtpEmail($data['otp'], $data['email'], OtpIdentifierEnum::EMAIL, OtpVerificationTypeEnum::REGISTER);
    }
}