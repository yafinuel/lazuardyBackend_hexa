<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Cache;

class ResetPasswordAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}

    public function execute(string $email, string $password, string $resetToken): void
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
