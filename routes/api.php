<?php

use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\AuthController;
use App\Domains\Authentication\Infrastructure\Delivery\Http\Controllers\SocialiteController;
use App\Domains\ClassDomain\Infrastructure\Delivery\Http\Controllers\ClassController;
use App\Domains\CourseCatalog\Infrastructure\Delivery\Http\Controllers\CourseCatalogController;
use App\Domains\Dashboard\Infrastructure\Delivery\Http\Controllers\DashboardController;
use App\Domains\Commerce\Infrastructure\Delivery\Http\Controllers\PaymentGatewayController;
use App\Domains\Notification\Infrastructure\Delivery\Http\Controllers\NotificationController;
use App\Domains\Package\Infrastructure\Delivery\Http\Controllers\PackageController;
use App\Domains\Presence\Infrastructure\Delivery\Http\Controllers\PresenceController;
use App\Domains\Review\Infrastructure\Delivery\Http\Controllers\ReviewController;
use App\Domains\Schedule\Infrastructure\Delivery\Http\Controllers\ScheduleController;
use App\Domains\Student\Infrastructure\Delivery\Http\Controllers\StudentController;
use App\Domains\Subject\Infrastructure\Delivery\Http\Controllers\SubjectController;
use App\Domains\Tutor\Infrastructure\Delivery\Http\Controllers\TutorController;
use App\Domains\User\Infrastructure\Delivery\Http\Controllers\UserController;
use App\Shared\Enums\RoleEnum;
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

Route::post('/register-otp/student-parents', [AuthController::class, 'registerParentStudentOtpEmail']);
Route::post('/verify-otp/student-parents', [AuthController::class, 'verifyOtpEmailForStudentParents']);
Route::post('/register/parent', [AuthController::class, 'parentRegister']);

// Finance
Route::get('/getBankList', [PaymentGatewayController::class, 'getBankList']);
Route::post('/validateBankAccount', [PaymentGatewayController::class, 'validateBankAccount']);
Route::post('/xendit/callback', [PaymentGatewayController::class, 'handlePaymentCallback']);

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
    Route::get('/getTutorById', [TutorController::class, 'getTutorById']);
    Route::patch('/updateProfilePhoto', [UserController::class, 'updateProfilePhoto']);
    Route::patch('/user/fcm-token', [NotificationController::class, 'updateFcmToken']);
    Route::delete('/user/fcm-token', [NotificationController::class, 'clearFcmToken']);
    
    // Package
    Route::get('/getPackages', [PackageController::class, 'index']);
    
    // Notification
    Route::get('/getNotificationByUserId', [NotificationController::class, 'getNotificationByUserId']);
    
    // Tutor
    Route::get('/getTutorByCriteria', [TutorController::class, 'getTutorByCriteria']);
    
    // Dashboard
    
    // Schedule
    Route::get('/schedule/getById', [ScheduleController::class, 'getScheduleById']);
    Route::get('/schedule/getTutorSchedulesByDay', [ScheduleController::class, 'getTutorSchedulesByDay']);
    Route::post('/schedule/cancel', [ScheduleController::class, 'cancelSchedule']);
    Route::get('/reports', [PresenceController::class, 'getPresenceByUserId']);
    Route::get('/schedules', [DashboardController::class, 'schedulePage']);
    
    Route::middleware('role:' . RoleEnum::STUDENT->value)->group(function (){
        Route::get('/student/biodata', [StudentController::class, 'getStudentById']);
        Route::put('/updateStudentBiodata', [StudentController::class, 'updateBiodata']);
        Route::get('/student/dashboard/homepage', [DashboardController::class, 'studentHomepage']);
        Route::post('/schedule/takeMeeting', [ScheduleController::class, 'createMeetingSchedule']);

        Route::patch('/student/schedule/mark-as-complete', [ScheduleController::class, 'markAsComplete']);

        Route::post('/student/review/create', [ReviewController::class, 'createReview']);

        // Order
        Route::post('/package/order', [PaymentGatewayController::class, 'orderPackage']);
    });

    Route::middleware('role:' . RoleEnum::TUTOR->value)->group(function (){
        Route::get('/meTutor', [TutorController::class, 'getTutorById']);
        Route::put('/tutor/profile', [TutorController::class, 'updateBiodata']);
        Route::get('/tutor/get-my-files', [TutorController::class, 'getTutorFileByUserId']);

        Route::post('tutor/presence/create', [PresenceController::class, 'createPresence']);

        // Route::get('tutor/schedules', [ScheduleController::class, 'getFilteredSchedulesByTutorId']);
        Route::patch('tutor/schedule/booking-confirmation', [ScheduleController::class, 'bookingConfirmation']);

        Route::put('/tutor/teaching-profile', [TutorController::class, 'updateTeachingProfile']);

        Route::get('/tutor/dashboard/homepage', [DashboardController::class, 'tutorHomepage']);

        Route::get('/tutor/review', [ReviewController::class, 'getTutorReviewsAsTutor']);
        
        Route::middleware('verified.tutor')->group(function () {
            // Route::get('/tutor/dashboard/schedule', [DashboardController::class, 'tutorSchedulePage']);
            // Route::get('/tutor/reports', [PresenceController::class, 'getAllReportsByTutorId']);
        });
    });
    Route::middleware('role:'. RoleEnum::PARENT->value)->group(function (){
        Route::get('/parent/dashboard/homepage', [DashboardController::class, 'parentHomepage']);
        Route::get('/parent/dashboard/profile-page', [StudentController::class, 'getStudentById']);
    });
});
