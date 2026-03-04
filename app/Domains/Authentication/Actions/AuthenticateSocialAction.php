<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\User;

class AuthenticateSocialAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}

    public function execute(object $socialUser, string $provider): User
    {
        $user = $this->repository->findByEmail($socialUser->getEmail());

        if ($user) {
            if (!$user->provider_id) {
                $this->repository->updateSocialId($user->id, $provider, $socialUser->getId());
            }
            return $user;
        }

        return $this->repository->create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'email' => $socialUser->getEmail(),
            $provider . '_id' => $socialUser->getId(),
            'password' => null,
        ]);
    }

}
