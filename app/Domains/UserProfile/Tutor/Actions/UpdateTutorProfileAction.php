<?php

namespace App\Domains\UserProfile\Tutor\Actions;

use App\Domains\UserProfile\Tutor\Ports\TutorRepositoryInterface;

class UpdateTutorProfileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(int $id, array $data): void
    {
        $data['home_address'] = [
            'province' => $data["province"],
            'regency' => $data["regency"],
            'district' => $data["district"],
            'subdistrict' => $data["subdistrict"],
        ];
        $this->repository->updateTutorBiodata($id, $data);
    }
}
