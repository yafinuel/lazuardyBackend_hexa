<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Domains\User\Ports\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginManualAction
{
    /**
     * Sistemnya login
     * 1. Cek apakah email terdaftar dan apakah password telah benar
     * 2. Bisa login
     */
    public function __construct(protected AuthenticationServicePort $service, protected UserRepositoryInterface $repository)
    {}
    
    public function execute(string $email, string $password): array
    {
        $user = $this->repository->getUserByEmail($email);

        if (!$user){
            throw ValidationException::withMessages([
                'email' => ['User tidak ditemukan']
            ]);
        } else if (!Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Password salah']
            ]);
        }

        $token = $this->service->getToken($user->id);

        return [
            "token" => $token,
            "role" => $user->role,
        ];
    }
}
