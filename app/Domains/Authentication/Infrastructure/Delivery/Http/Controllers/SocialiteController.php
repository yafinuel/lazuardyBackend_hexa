<?php

namespace App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers;

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
            $data = $action->execute($socialUser, $provider);
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        }
    }
}