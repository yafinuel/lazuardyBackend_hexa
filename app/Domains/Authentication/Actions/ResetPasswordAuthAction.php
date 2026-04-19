<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;

class ResetPasswordAuthAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data): void
    {
        $this->service->resetPassword($data['email'], $data['reset_token'], $data['password']);
    }
}