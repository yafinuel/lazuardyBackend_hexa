<?php

namespace App\Domains\Penalty\Actions;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use Carbon\Carbon;

class GetSanctionAction
{
    public function __construct(protected PenaltyRepositoryInterface $repository) {}

    public function execute(int $userId): ?Carbon
    {
        return $this->repository->getUserSanction($userId);
    }
}