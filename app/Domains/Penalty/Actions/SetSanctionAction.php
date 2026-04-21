<?php

namespace App\Domains\Penalty\Actions;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use Carbon\Carbon;

class SetSanctionAction
{
    public function __construct(protected PenaltyRepositoryInterface $repository) {}

    public function execute(int $userId, Carbon $sanctionEndDate): bool
    {
        return $this->repository->setSanction($userId, $sanctionEndDate);
    }
}