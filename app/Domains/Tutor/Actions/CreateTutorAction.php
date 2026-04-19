<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Shared\Enums\TutorStatusEnum;

class CreateTutorAction
{
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(int $userId,array $data): int
    {
        $filter = [
            'education' => $data['education'] ?? null,
            'description' => $data['description'] ?? null,
            'bank_code' => $data['bank_code'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'learning_method' => $data['learning_method'] ?? null,
            'status' => $data['status'] ?? TutorStatusEnum::PENDING,
        ];
        return $this->repository->createTutor($userId, $filter);
    }
}