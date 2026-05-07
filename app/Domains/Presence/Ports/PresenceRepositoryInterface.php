<?php

namespace App\Domains\Presence\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PresenceRepositoryInterface
{
    public function createPresence(int $scheduleId, int $tutorId, int $studentId, string $topic, string $notes): void;
    public function getPresencesByUserId(int $userId, ?array $filters, int $perPage = 10): LengthAwarePaginator;
}