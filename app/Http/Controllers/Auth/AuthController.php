<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\LoginManualAction;
use App\Domains\Authentication\Actions\SendOtpAction;
use App\Domains\Authentication\Actions\StudentRegisterAction;
use App\Domains\Authentication\Actions\VerifyOtpAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, LoginManualAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $result = $action->execute($request->email, $request->password);

        return response()->json([
            'status' => 'success',
            'access_token' => $result['token'],
            'token_type' => 'Bearer'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function requestOtpEmail(Request $request, SendOtpAction $action)
    {
        $request->validate([
            "email" => ['required', 'email'],
        ]);
        
        try {
            $otp = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::REGISTER->value);

            return response()->json([
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() // Pesan dari throw di Action tadi
            ], 500);
        }
    }

    public function verifyOtpEmail(Request $request, VerifyOtpAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string']
        ]);

        try {
            $result = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::REGISTER->value, $request->otp);
            
            return response()->json([
                'status' => $result['status'],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    
    public function studentRegister(Request $request, StudentRegisterAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string'],
            'class_id' => ['required', 'integer'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
            'telephone_number' => ['required', 'string'],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
            'google_id' => ['nullable', 'string'],
            'facebook_id' => ['nullable', 'string'],
        ]);

        $token = $action->execute($data);

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function tutorRegister(Request $request, )
    {
        
    }
}
