<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\LoginManualAction;
use App\Domains\Authentication\Actions\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\OtpIdentifierEnum;
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

    public function requestOtpEmail(Request $request, SendOtpAction $action)
    {
        $request->validate([
            "email" => ['required', 'email']
        ]);
        
        try {
            $otp = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, 'register');

            return response()->json([
                'status' => 'success',
                'message' => 'Kode OTP berhasil dikirim ke ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() // Pesan dari throw di Action tadi
            ], 500);
        }
    }
}
