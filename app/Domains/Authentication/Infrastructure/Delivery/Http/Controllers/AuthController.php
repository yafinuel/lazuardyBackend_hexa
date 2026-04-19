<?php

namespace App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Authentication\Actions\ForgotPasswordOtpEmailAction;
use App\Domains\Authentication\Actions\LoginManualAction;
use App\Domains\Authentication\Actions\RegisterOtpEmailAction;
use App\Domains\Authentication\Actions\ResetPasswordAction;
use App\Domains\Authentication\Actions\ResetPasswordAuthAction;
use App\Domains\Authentication\Actions\SendOtpAction;
use App\Domains\Authentication\Actions\StudentRegisterPageAction;
use App\Domains\Authentication\Actions\TutorRegisterPageAction;
use App\Domains\Authentication\Actions\VerifyOtpAction;
use App\Domains\Authentication\Actions\VerifyOtpEmailForgotPasswordAction;
use App\Domains\Authentication\Actions\VerifyOtpRegisterEmailAction;
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
            'role' => $result['role'],
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

    public function registerOtpEmail(Request $request, RegisterOtpEmailAction $action)
    {
        $data = $request->validate([
            "email" => ['required', 'email'],
        ]);
        
        try {
            $action->execute($data);

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

    public function forgotPasswordOtpEmail(Request $request, ForgotPasswordOtpEmailAction $action)
    {
        $data = $request->validate([
            "email" => ['required', 'email'],
        ]);
        
        try {
            $action->execute($data);

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

    public function verifyOtpRegisterEmail(Request $request, VerifyOtpRegisterEmailAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string']
        ]);

        try {
            $action->execute($data);
            
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

    public function forgotPasswordVerifyOtpEmail(Request $request, VerifyOtpEmailForgotPasswordAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string']
        ]);

        try {
            $resetToken = $action->execute($data);
            
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

    public function forgotPasswordResetPassword(Request $request, ResetPasswordAuthAction $action)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'reset_token' => ['required', 'string'],
        ]);

        try {
            $action->execute($data);
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
    
    public function studentRegister(Request $request, StudentRegisterPageAction $action)
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

            $result = $action->execute($data);

            return response()->json([
                'status' => 'success',
                'access_token' => $result['token'],
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

    public function tutorRegister(Request $request, TutorRegisterPageAction $action)
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
            'profile_photo' => ['nullable', 'file', 'mimes:jpg,png,jpeg,svg,webp'],
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

            $result = $action->execute($data);
            
            return response()->json([
                'status' => 'success',
                'access_token' => $result['token'],
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
