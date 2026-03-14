<?php

namespace App\Domains\UserProfile\Student\Actions;

use App\Domains\UserProfile\Student\Ports\StudentRepositoryInterface;

class StudentBiodataAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected StudentRepositoryInterface $repository) {}

    public function execute(int $studentId)
    {
        return $this->repository->getStudentProfile($studentId);
    }
}
