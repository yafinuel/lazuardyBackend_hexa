<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Shared\Enums\RoleEnum;
use Nette\Schema\ValidationException;

class ParentRegisterPageAction
{
    public function __construct(protected AuthenticationServicePort $service) {}

    public function execute(array $data)
    {
        $data = [
            'email' => $data['email'],
            'password' => $data['password'],
            'child_email' => $data['child_email'],
            'role' => RoleEnum::PARENT,
        ];

        $child = $this->service->getUserByEmail($data['child_email']);

        if (!$child || $child->role != RoleEnum::STUDENT) {
            throw new ValidationException('Child email is not associated with a student account.');
        }

        $data['student_id'] = $child->id;

        return $this->service->parentRegister($data);
    }
}