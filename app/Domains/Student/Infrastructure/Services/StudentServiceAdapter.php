<?php

namespace App\Domains\Student\Infrastructure\Services;

use App\Domains\Student\Ports\StudentServicePort;
use App\Domains\User\Actions\UpdateUserAction;

class StudentServiceAdapter implements StudentServicePort
{
    public function __construct(
        protected UpdateUserAction $updateUserAction
    ) {}

    public function updateUser(int $userId, array $data): void
    {
        $this->updateUserAction->execute($userId, $data);
    }
}