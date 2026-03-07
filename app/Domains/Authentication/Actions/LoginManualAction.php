<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginManualAction
{
    /**
     * Sistemnya login
     * 1. Cek apakah email terdaftar dan apakah password telah benar
     * 2. Bisa login
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}
    
    public function execute(string $email, string $password): User
    {
        $user = $this->repository->findByEmail($email);

        if (!$user){
            throw ValidationException::withMessages([
                'email' => ['User tidak ditemukan']
            ]);
        } else if (!Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Password salah']
            ]);
        }

        return $user;
    }
}
