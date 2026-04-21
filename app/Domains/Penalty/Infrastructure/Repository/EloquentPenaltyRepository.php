<?php

namespace App\Domains\Penalty\Infrastructure\Repository;

use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class EloquentPenaltyRepository implements PenaltyRepositoryInterface
{
    public function getUserWarning(string $userId): int
    {
        $user = User::findOrFail($userId);
        return $user->warning;
    }

    public function getUserSanction(string $userId): ?Carbon
    {
        $user = User::findOrFail($userId);
        return $user->sanction;
    }

    public function addWarning(string $userId): bool
    {
        $user = User::findOrFail($userId);
        $user->warning += 1;
        return $user->save();
    }
    public function setSanction(string $userId, Carbon $sanctionEndDate): bool
    {
        $user = User::findOrFail($userId);
        $user->sanction = $sanctionEndDate;
        return $user->save();
    }
}