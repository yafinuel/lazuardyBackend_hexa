<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Domains\Tutor\Ports\TutorServicePort;
use Exception;

class UpdateTutorProfileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TutorRepositoryInterface $repository, protected TutorServicePort $service) {}

    public function execute(int $tutorId, array $data): void
    {
        $data['home_address'] = [
            'province' => $data["province"],
            'regency' => $data["regency"],
            'district' => $data["district"],
            'subdistrict' => $data["subdistrict"],
        ];

        $tutorData = [
            'bank_code' => $data['bank_code'],
            'account_number' => $data['account_number'],
        ];

        $userData = [
            'name' => $data['name'],
            'telephone_number' => $data['telephone_number'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'home_address' => $data['home_address'],
        ];
        
        try {
            $this->service->updateUser($tutorId, $userData);
            $this->repository->update($tutorId, $tutorData);
        } catch (Exception $e) {
            throw new Exception("Failed to update tutor profile: " . $e->getMessage());
        }
    }
}