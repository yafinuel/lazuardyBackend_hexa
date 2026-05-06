<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\RoleEnum;

class ParentRegisterPageAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        $data = [
            'email' => $data['email'],
            'password' => $data['password'],
            'student_id' => $data['student_id'],
            'role' => RoleEnum::PARENT,
        ];

        return $this->service->parentRegister($data);
    }
}