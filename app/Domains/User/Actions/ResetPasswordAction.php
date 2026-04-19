<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Ports\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Cache;

class ResetPasswordAction
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function execute(string $email, string $resetToken, string $password): void
    {
        $cacheKey = "password_reset_token_" . $email;
        $storedToken = Cache::get($cacheKey);

        if (!$storedToken || $storedToken !== $resetToken) {
            throw new Exception("Token reset tidak valid atau sudah kedaluwarsa.", 403);
        }

        $this->repository->resetPassword($email, $password);
        
        Cache::forget($cacheKey);
    }
}