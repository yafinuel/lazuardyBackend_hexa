<?php

use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\AuthController;
use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\SocialiteController;
use App\Domains\ClassDomain\Infrastructure\Delivery\Http\Controllers\ClassController;
use App\Domains\Finance\Infrastructure\Delivery\Http\Controllers\PaymentGatewayController;
use App\Domains\Package\Infrastructure\Delivery\Http\Controllers\PackageController;
use App\Domains\Subject\Infrastructure\Delivery\Http\Controllers\SubjectController;
use App\Domains\UserProfile\Student\Infrastructure\Delivery\Http\Controllers\StudentController;
use App\Domains\UserProfile\Tutor\Infrastructure\Delivery\Http\Controllers\TutorController;
use App\Domains\UserProfile\User\Infrastructure\Delivery\Http\Controllers\UserController;
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

// Class
Route::get('/jenjang', [ClassController::class, 'getClassLevels']);
Route::get('/getAllClass', [ClassController::class, 'getAllClass']);
Route::get('/getClassByLevel', [ClassController::class, 'getClassByLevel']);

// Subject
Route::get('/getAllSubjects', [SubjectController::class, 'getAllSubjects']);
Route::get('/getSubjectByClass', [SubjectController::class, 'getSubjectByClass']);
Route::get('/getSubjectByLevel', [SubjectController::class, 'getSubjectByLevel']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Profile
    Route::get('/studentBiodata', [StudentController::class, 'biodata']);
    Route::patch('/updateStudentBiodata', [StudentController::class, 'updateBiodata']);
    Route::get('/tutorBiodata', [TutorController::class, 'biodata']);
    Route::get('/getTutorFile', [TutorController::class, 'getTutorFile']);
    Route::patch('/updateTutorBiodata', [TutorController::class, 'updateBiodata']);
    Route::patch('/updateProfilePhoto', [UserController::class, 'updateProfilePhoto']);

    // Package
    Route::get('/getPackages', [PackageController::class, 'index']);
});
