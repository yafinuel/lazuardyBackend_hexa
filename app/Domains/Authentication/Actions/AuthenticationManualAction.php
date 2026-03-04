<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationManualAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}
    
    public function execute(string $email, string $password): User
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Kresidensial yang diberikan salah']
            ]);
        }

        return $user;
    }
}
