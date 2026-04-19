<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Ports\StudentRepositoryInterface;

class CreateStudentAction
{
    public function __construct(protected StudentRepositoryInterface $repository) {}

    public function execute(int $userId, array $data): int
    {
        return $this->repository->createStudent($userId, $data);
    }
}