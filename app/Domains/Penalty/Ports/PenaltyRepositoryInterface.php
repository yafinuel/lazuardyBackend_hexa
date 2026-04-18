<?php

namespace App\Domains\Penalty\Ports;

use Carbon\Carbon;

interface PenaltyRepositoryInterface
{
    public function getUserWarning(string $userId): int;
    public function getUserSanction(string $userId): ?Carbon;
    public function addWarning(string $userId): void;
    public function setSanction(string $userId, ?string $sanctionEndDate): void;
}