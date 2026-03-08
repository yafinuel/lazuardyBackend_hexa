<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class AuthenticateSocialAction
{
    /**
     * Logika autentikasi OAuth
     * 1. Jika email ditemukan tetapi "provider_id" tidak ada, maka akan dijalankan metode updateSocialId dan langsung login
     * 2. Jika email ditemukan dan sudah memiliki "provider_id" maka akan login
     * 3. Jika email tidak ditemukan maka akan mengirim data ke API berupa identitas yang didapatkan dari OAuth dan selanjutnya akan diarahkan ke biodata.
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {}

    public function execute(object $socialUser, string $provider)
    {
        $user = $this->repository->findByEmail($socialUser->getEmail());

        if ($user) {
            if (!$user->$provider + '_id') {
                // provider without "_id".
                $this->repository->updateSocialId($user->id, $provider, $socialUser->getId());
            }
            $token = $this->repository->getToken($user->id);
            return [
                'message' => 'Authorized',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        }

        if ($provider == 'google'){
            return [
                'message' => 'Unauthorized',
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'google_id' => $socialUser->getId(),
                'facebook_id' => null,
                'password' => Str::random(16),
            ];
        } else if ($provider == "facebook"){
            return [
                'message' => 'Unauthorized',
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'google_id' => null,
                'facebook_id' => $socialUser->getId(),
                'password' => Str::random(16),
            ];
        }
    }

}
