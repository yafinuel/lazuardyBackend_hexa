<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class StudentSchedulePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute()
    {
        // return ;
    }
}