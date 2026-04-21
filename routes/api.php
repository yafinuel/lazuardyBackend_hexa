<?php

use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\AuthController;
use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\SocialiteController;
use App\Domains\ClassDomain\Infrastructure\Delivery\Http\Controllers\ClassController;
use App\Domains\CourseCatalog\Infrastructure\Delivery\Http\Controllers\CourseCatalogController;
use App\Domains\Dashboard\Infrastructure\Delivery\Http\Controllers\DashboardController;
use App\Domains\Finance\Infrastructure\Delivery\Http\Controllers\PaymentGatewayController;
use App\Domains\Notification\Infrastructure\Delivery\Http\Controllers\NotificationController;
use App\Domains\Package\Infrastructure\Delivery\Http\Controllers\PackageController;
use App\Domains\Schedule\Infrastructure\Delivery\Http\Controllers\ScheduleController;
use App\Domains\Student\Infrastructure\Delivery\Http\Controllers\StudentController;
use App\Domains\Subject\Infrastructure\Delivery\Http\Controllers\SubjectController;
use App\Domains\Tutor\Infrastructure\Delivery\Http\Controllers\TutorController;
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
Route::get('/getUniqueSubjectByLevel', [SubjectController::class, 'getUniqueSubjectByLevel']);

// Course Catalog
Route::get('/filterCategoryPage', [CourseCatalogController::class, 'filterCategoryPageAction']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Profile
    Route::get('/meStudent', [StudentController::class, 'meStudent']);
    Route::patch('/updateStudentBiodata', [StudentController::class, 'updateBiodata']);
    Route::get('/meAsTutor', [TutorController::class, 'getTutorById']);
    Route::get('/getTutorById', [TutorController::class, 'getTutorById']);
    Route::get('/getTutorFile', [TutorController::class, 'getTutorFile']);
    Route::patch('/updateTutorBiodata', [TutorController::class, 'updateBiodata']);

    // Package
    Route::get('/getPackages', [PackageController::class, 'index']);

    // Notification
    Route::get('/getNotificationByUserId', [NotificationController::class, 'getNotificationByUserId']);
    
    // Tutor
    Route::get('/getTutorByCriteria', [TutorController::class, 'getTutorByCriteria']);

    // Dashboard
    Route::get('/student/dashboard/homepage', [DashboardController::class, 'studentHomepage']);
    Route::get('/student/dashboard/schedule', [DashboardController::class, 'studentSchedulePage']);

    // Schedule
    Route::get('/schedule/getById', [ScheduleController::class, 'getScheduleById']);
    Route::post('/schedule/cancel', [ScheduleController::class, 'cancelSchedule']);
});
