<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Domains\User\Ports\UserRepositoryInterface;
use App\Shared\Enums\RoleEnum;
use Carbon\Carbon;

class TutorRegisterPageAction
{
    public function __construct(protected AuthenticationServicePort $service, protected UserRepositoryInterface $userRepository) {}

    public function execute(array $data): array
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

        $tutorData = [
            'description' => $data['description'],
            'bank_code' => $data['bank_code'],
            'account_number' => $data['account_number'],
            'learning_method' => $data['learning_method'],
        ];

        $subjectData = $data['subject_id'];
        $scheduleData = $data['schedules'];

        $fileData = [
            'curriculum_vitae' => $data['curriculum_vitae'],
            'id_card' => $data['id_card'],
            'certificate' => $data['certificate'],
        ];

        $userId = $this->service->tutorRegister($userData, $tutorData, $subjectData, $scheduleData, $fileData);
        $user = $this->userRepository->getUserById($userId);
        $token = $this->service->getToken($userId);

        return [
            'token' => $token,
            'role' => $user->role,
        ];
    }
}