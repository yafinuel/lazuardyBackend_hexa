<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Domains\User\Ports\UserRepositoryInterface;
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
    public function __construct(protected AuthenticationServicePort $service, protected UserRepositoryInterface $userRepository)
    {}

    public function execute(object $socialUser, string $provider)
    {
        $userEmail = $socialUser->getEmail();
        $socialiteId = $socialUser->getId();
        $socialiteName = $socialUser->getName();

        $user = $this->userRepository->getUserByEmail($userEmail);

        if ($user) {
            $providerField = $provider . '_id';
            if (!$user->$providerField) {
                // provider without "_id".
                $this->userRepository->updateSocialId($user->id, $provider, $socialiteId);
            }
            $token = $this->service->getToken($user->id);
            return [
                'message' => 'Authorized',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        }

        if ($provider == 'google'){
            return [
                'message' => 'Unauthorized',
                'name' => $socialiteName,
                'email' => $userEmail,
                'google_id' => $socialiteId,
                'facebook_id' => null,
                'password' => Str::random(16),
            ];
        } else if ($provider == "facebook"){
            return [
                'message' => 'Unauthorized',
                'name' => $socialiteName,
                'email' => $userEmail,
                'google_id' => null,
                'facebook_id' => $socialiteId,
                'password' => Str::random(16),
            ];
        }
    }

}
