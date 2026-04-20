<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use Carbon\Carbon;

class StudentSchedulePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $userId, array $data)
    {
        $date = Carbon::parse($data['date']);
        $paginate = $data['paginate'] ?? 10;
        return $this->service->studentSchedulePage($userId, $date, $paginate);
    }
}