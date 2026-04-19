<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;

class StudentRegisterPageAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => Carbon::now(),
            'home_address' => [
                'province' => $data["province"],
                'regency' => $data["regency"],
                'district' => $data["district"],
                'subdistrict' => $data["subdistrict"],
            ],
            'role' => RoleEnum::TUTOR,
            'google_id' => $data['google_id'] ?? null,
            'facebook_id' => $data['facebook_id'] ?? null,
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'telephone_number' => $data['telephone_number'],
            'profile_photo' => $data['profile_photo'] ?? null,
        ];

        $studentData = [
            'class_id' => $data['class_id'],
        ];

        $userId = $this->service->studentRegister($userData, $studentData);
        $token = $this->service->getToken($userId);
        return [
            'token' => $token,
            'role' => RoleEnum::STUDENT->value,
        ];
    }
}