<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Ports\StudentRepositoryInterface;

class UpdateStudentBiodataAction
{
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