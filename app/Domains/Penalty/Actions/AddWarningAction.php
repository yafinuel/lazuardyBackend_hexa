<?php

namespace App\Domains\Penalty\Actions;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;

class AddWarningAction
{
    public function __construct(protected PenaltyRepositoryInterface $repository) {}

    public function execute(int $userId): bool
    {
        
        return $this->repository->addWarning($userId);
    }
}