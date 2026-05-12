<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Ports\StudentRepositoryInterface;

class UpdateStudentSessionAction
{
    public function __construct(protected StudentRepositoryInterface $repository) {}

    public function execute(int $userId, int $sessionToAdd = 1)
    {
        $student = $this->repository->getStudentById($userId);
        $studentSession = $student->session + $sessionToAdd;

        $this->repository->update($userId, [
            'session' => $studentSession
        ]);
    }
}