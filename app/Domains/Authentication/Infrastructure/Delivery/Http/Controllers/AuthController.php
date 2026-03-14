<?php

namespace App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Authentication\Actions\LoginManualAction;
use App\Domains\Authentication\Actions\ResetPasswordAction;
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
            'password' => ['required', 'string']
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

    public function registerOtpEmail(Request $request, SendOtpAction $action)
    {
        $request->validate([
            "email" => ['required', 'email'],
        ]);
        
        try {
            $otp = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::REGISTER->value, "Kode Verifikasi OTP Lazuardy App", "Verifikasi Akun");

            return response()->json([
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal mengirim OTP",
                'debug_error' => $e->getMessage(),
            ], $e->getCode()?: 500);
        }
    }

    public function forgotPasswordOtpEmail(Request $request, SendOtpAction $action)
    {
        $request->validate([
            "email" => ['required', 'email'],
        ]);
        
        try {
            $otp = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::FORGOT_PASSWORD->value, "Kode OTP Lupa Password Lazuardy App", "Forgot Password");

            return response()->json([
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal mengirim OTP",
                'debug_error' => $e->getMessage(),
            ], $e->getCode()?: 500);
        }
    }

    public function verifyOtpRegisterEmail(Request $request, VerifyOtpAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string']
        ]);

        try {
            $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::REGISTER->value, $request->otp);
            
            return response()->json([
                'status' => 'success',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal memverifikasi OTP",
                'debug_error' => $e->getMessage(),
            ], $e->getCode()?: 500);
        }
    }

    public function forgotPasswordVerifyOtpEmail(Request $request, VerifyOtpAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string']
        ]);

        try {
            $resetToken = $action->execute($request->email, OtpIdentifierEnum::EMAIL->value, OtpVerificationTypeEnum::FORGOT_PASSWORD->value, $request->otp);
            
            return response()->json([
                'status' => 'success',
                'reset_token' => $resetToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Gagal memverifikasi OTP",
                'debug_error' => $e->getMessage(),
            ], $e->getCode()?: 500);
        }
    }

    public function forgotPasswordResetPassword(Request $request, ResetPasswordAction $action)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'reset_token' => ['required', 'string'],
        ]);

        try {
            $action->execute($request['email'], $request['password'], $request['reset_token']);
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'debug_error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
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

        try {

            $token = $action->execute($data);

            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan registrasi siswa.',
                'debug_error' => $e->getMessage(),
            ], 500);
        }

    }

    public function tutorRegister(Request $request, TutorRegisterAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
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
            
            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan registrasi tentor.',
                'debug_error' => $e->getMessage(),
            ], 500);
        }
    }
}
