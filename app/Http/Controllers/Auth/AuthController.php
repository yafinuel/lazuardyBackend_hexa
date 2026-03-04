<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\AuthenticationManualAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, AuthenticationManualAction $action)
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
}
