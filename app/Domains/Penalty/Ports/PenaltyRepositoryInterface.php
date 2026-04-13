<?php

namespace App\Domains\Penalty\Ports;

interface PenaltyRepositoryInterface
{
    public function addWarning(string $userId): void;
    public function setSanction(string $userId, ?string $sanctionEndDate): void;
}