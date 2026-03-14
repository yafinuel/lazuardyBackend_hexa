<?php

namespace App\Providers;

use App\Domains\Authentication\Infrastructure\Messaging\LaravelMailer;
use App\Domains\Authentication\Infrastructure\Repository\EloquentOtpRepository;
use App\Domains\Authentication\Infrastructure\Repository\EloquentUserRepository;
use App\Domains\Authentication\Ports\MailerInterface;
use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Domains\Finance\Infrastructure\External\XenditBankAdapter;
use App\Domains\Finance\Ports\BankValidatorInterface;
use App\Shared\Infrastructure\Queues\LaravelTaskQueue;
use App\Shared\Infrastructure\Repository\EloquentFileRepository;
use App\Shared\Infrastructure\Storage\LaravelFileStorage;
use App\Shared\Ports\FileRepositoryInterface;
use App\Shared\Ports\FileStorageInterface;
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
            OtpRepositoryInterface::class,
            EloquentOtpRepository::class
        );

        $this->app->bind(
            MailerInterface::class,
            LaravelMailer::class
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
