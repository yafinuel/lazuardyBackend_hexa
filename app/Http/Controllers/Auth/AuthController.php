<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\LoginManualAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, LoginManualAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = $action->execute($request->email, $request->pasword);
        $token = $user->createToken('manual_auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function register(Request $request, )
    {
        
    }
}
