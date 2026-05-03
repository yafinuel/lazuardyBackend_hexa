<?php

namespace App\Domains\Tutor\Infrastructure\Services;

use App\Domains\Tutor\Ports\TutorServicePort;
use App\Domains\User\Actions\UpdateUserAction;

class TutorServiceAdapter implements TutorServicePort
{
    public function __construct(
        protected UpdateUserAction $updateUserAction
    ) {}

    public function updateUser(int $userId, array $data): void
    {
        $this->updateUserAction->execute($userId, $data);
    }
}