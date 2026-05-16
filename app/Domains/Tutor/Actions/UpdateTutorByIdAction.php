<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;

class UpdateTutorByIdAction
{    
    public function __construct(protected TutorRepositoryInterface $repository) {}

    public function execute(int $userId, array $data)
    {
        return $this->repository->update($userId, $data);
    }
}