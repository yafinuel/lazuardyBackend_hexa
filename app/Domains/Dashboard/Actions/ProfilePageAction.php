<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Shared\Enums\RoleEnum;

class ProfilePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $userId)
    {
        $user = $this->service->getUserById($userId);
        
        if($user->role == RoleEnum::PARENT){
            $parent = $this->service->parentBiodata($userId);
            $userId = $parent->studentId;
        }

        
    }
}