<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Finance\PaymentGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registerOtpEmail', [AuthController::class, 'registerOtpEmail']);
Route::post('/verifyOtpRegisterEmail', [AuthController::class, 'verifyOtpRegisterEmail']);
Route::post('/forgotPasswordOtpEmail', [AuthController::class, 'forgotPasswordOtpEmail']);
Route::post('/forgotPasswordVerifyOtpEmail', [AuthController::class, 'forgotPasswordVerifyOtpEmail']);
Route::post('/forgotPasswordResetPassword', [AuthController::class, 'forgotPasswordResetPassword']);
Route::post('/studentRegister', [AuthController::class, 'studentRegister']);
Route::post('/tutorRegister', [AuthController::class, 'tutorRegister']);

// Finance
Route::get('/getBankList', [PaymentGatewayController::class, 'getBankList']);
Route::post('/validateBankAccount', [PaymentGatewayController::class, 'validateBankAccount']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});