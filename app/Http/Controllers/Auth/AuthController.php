<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Authentication\Actions\LoginManualAction;
use App\Domains\Authentication\Actions\SendOtpAction;
use App\Domains\Authentication\Actions\StudentRegisterAction;
use App\Domains\Authentication\Actions\TutorRegisterAction;
use App\Domains\Authentication\Actions\VerifyOtpAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\GenderEnum;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

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
            'gender' => ['required', new Enum(GenderEnum::class)],
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

    public function tutorRegister(Request $request, TutorRegisterAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
            'gender' => ['required', new Enum(GenderEnum::class)],
            'description' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'telephone_number' => ['required', 'string'],
            'bank_code' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
            'google_id' => ['nullable', 'string'],
            'facebook_id' => ['nullable', 'string'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'learning_method' => ['required', 'array'],
            'schedules' => ['required', 'array'],
            'schedules.*.day' => ['required', 'string'],
            'schedules.*.time' => ['required', 'date_format:H:i'],
            'curriculum_vitae' => ['required', 'file', 'mimes:pdf'],
            'id_card' => ['required', 'file', 'mimes:pdf,jpg,png'],   
            'certificate' => ['required', 'file', 'mimes:pdf'],
        ]);

        try {
            $token = $action->execute($data);
        } catch (Exception $e) {
            throw new Exception("Detail Error: " . $e->getMessage(), 500);
        }
        
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
}
