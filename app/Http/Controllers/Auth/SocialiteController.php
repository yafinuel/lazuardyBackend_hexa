<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\AuthenticateSocialAction;
use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;


class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function callback(string $provider,AuthenticateSocialAction $action)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = $action->execute($socialUser, $provider);
            $token = $user->createToken('api_auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        }
    }
}