<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Shared\Enums\RoleEnum;

class SchedulePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $userId, array $data, ?int $paginate = 10)
    {
        $filters['date'] = $data['date'] ?? null;
        $user = $this->service->getUserById($userId);
        if ($user->role == RoleEnum::PARENT) {
            $userId = $this->service->parentBiodata($userId)->studentId; // Ensure parent biodata is loaded to get studentId
        }
        return $this->service->getSchedulesByUserId($userId, $filters, $paginate);
    }
}