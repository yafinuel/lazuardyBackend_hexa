<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use App\Shared\Enums\RoleEnum;
use Nette\Schema\ValidationException;

class RegisterStudentParentOtpAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        $email = $data['email'];
        $user = $this->service->getUserByEmail($email);

        if ($user->role !== RoleEnum::STUDENT) {
            throw new ValidationException("Email tujuan tidak terdaftar sebagai siswa");
        }

        $this->service->registerOtpEmail($email, OtpIdentifierEnum::EMAIL, OtpVerificationTypeEnum::VERIFY_PARENT, "Kode Verifikasi OTP Lazuardy App", "Verifikasi Orang tua dan anak");
    }
}