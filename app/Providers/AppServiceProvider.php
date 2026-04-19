<?php

namespace App\Providers;

use App\Domains\Authentication\Infrastructure\Services\AuthenticationServiceAdapter;
use App\Domains\Authentication\Ports\AuthenticationServicePort;
use App\Domains\ClassDomain\Infrastructure\Repository\EloquentClassRepository;
use App\Domains\ClassDomain\Ports\ClassRepositoryInterface;
use App\Domains\CourseCatalog\Infrastructure\Service\CourseCatalogServiceAdapter;
use App\Domains\CourseCatalog\Ports\CourseCatalogServicePort;
use App\Domains\Dashboard\Infrastructure\Services\DashboardServiceAdapter;
use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\FileManager\Infrastructure\Repository\EloquentFileRepository;
use App\Domains\FileManager\Infrastructure\Storages\LaravelFileStorage;
use App\Domains\FileManager\Ports\FileRepositoryInterface;
use App\Domains\FileManager\Ports\FileStorageInterface;
use App\Domains\Finance\Infrastructure\External\XenditBankAdapter;
use App\Domains\Finance\Ports\BankValidatorInterface;
use App\Domains\MailManager\Infrastructure\Messaging\LaravelMailer;
use App\Domains\MailManager\Ports\MailerInterface;
use App\Domains\Notification\Infrastructure\External\Firebase\FcmAdapter;
use App\Domains\Notification\Infrastructure\Repository\EloquentNotificationRepository;
use App\Domains\Notification\Ports\NotificationGatewayInterface;
use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Domains\OtpManager\Infrastructure\Repository\EloquentOtpRepository;
use App\Domains\OtpManager\Ports\OtpRepositoryInterface;
use App\Domains\Package\Infrastructure\Repository\EloquentPackageRepository;
use App\Domains\Package\Ports\PackageRepositoryInterface;
use App\Domains\Penalty\Infrastructure\Repository\EloquentPenaltyRepository;
use App\Domains\Penalty\Ports\PenaltyRepositoryInterface;
use App\Domains\Schedule\Infrastructure\Repository\EloquentScheduleRepository;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Student\Infrastructure\Repository\EloquentStudentRepository;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Domains\Subject\Infrastructure\Repository\EloquentSubjectRepository;
use App\Domains\Subject\Ports\SubjectRepositoryInterface;
use App\Domains\Tutor\Infrastructure\Repository\EloquentTutorRepository;
use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Domains\User\Infrastructure\Repository\EloquentUserRepository;
use App\Domains\User\Ports\UserRepositoryInterface;
use App\Domains\UserProfile\User\Infrastructure\Repository\EloquentUserRepository as EloquentUserProfileRepository;
use App\Domains\UserProfile\User\Ports\UserRepositoryInterface as UserProfileRepositoryInterface;
use App\Shared\Infrastructure\Queues\LaravelTaskQueue;
use App\Shared\Ports\TaskQueueInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            BankValidatorInterface::class,
            XenditBankAdapter::class
        );
        
        $this->app->bind(
            FileStorageInterface::class,
            LaravelFileStorage::class
        );

        $this->app->bind(
            FileRepositoryInterface::class,
            EloquentFileRepository::class
        );

        $this->app->bind(
            TaskQueueInterface::class,
            LaravelTaskQueue::class
        );

        $this->app->bind(
            StudentRepositoryInterface::class,
            EloquentStudentRepository::class
        );

        $this->app->bind(
            UserProfileRepositoryInterface::class,
            EloquentUserProfileRepository::class
        );

        $this->app->bind(
            PackageRepositoryInterface::class,
            EloquentPackageRepository::class
        );

        $this->app->bind(
            ClassRepositoryInterface::class,
            EloquentClassRepository::class
        );

        $this->app->bind(
            SubjectRepositoryInterface::class,
            EloquentSubjectRepository::class
        );

        $this->app->bind(
            CourseCatalogServicePort::class,
            CourseCatalogServiceAdapter::class
        );

        $this->app->bind(
            NotificationGatewayInterface::class,
            FcmAdapter::class
        );

        $this->app->bind(
            NotificationRepositoryInterface::class,
            EloquentNotificationRepository::class
        );

        $this->app->bind(
            TutorRepositoryInterface::class,
            EloquentTutorRepository::class
        );

        $this->app->bind(
            DashboardServicePort::class,
            DashboardServiceAdapter::class
        );

        $this->app->bind(
            PenaltyRepositoryInterface::class,
            EloquentPenaltyRepository::class
        );

        $this->app->bind(
            AuthenticationServicePort::class,
            AuthenticationServiceAdapter::class
        );

        $this->app->bind(
            ScheduleRepositoryInterface::class,
            EloquentScheduleRepository::class
        );

        $this->app->bind(
            OtpRepositoryInterface::class,
            EloquentOtpRepository::class
        );

        $this->app->bind(
            MailerInterface::class,
            LaravelMailer::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
