<?php

namespace App\Domains\UserProfile\Student\Actions;

use App\Domains\UserProfile\Student\Ports\StudentRepositoryInterface;

class UpdateStudentProfileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected StudentRepositoryInterface $repository) {}

    public function execute(int $studentId, array $data): void
    {
        
        $data['home_address'] = [
            'province' => $data["province"],
            'regency' => $data["regency"],
            'district' => $data["district"],
            'subdistrict' => $data["subdistrict"],
        ];

        $this->repository->updateStudentProfile($studentId, $data);
    }
}
