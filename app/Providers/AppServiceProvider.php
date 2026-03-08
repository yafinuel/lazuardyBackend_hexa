<?php

namespace App\Providers;

use App\Domains\Authentication\Infrastructure\Messaging\LaravelMailer;
use App\Domains\Authentication\Infrastructure\Repository\EloquentOtpRepository;
use App\Domains\Authentication\Infrastructure\Repository\EloquentUserRepository;
use App\Domains\Authentication\Ports\MailerInterface;
use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Domains\Finance\Infrastructure\XenditBankAdapter;
use App\Domains\Finance\Ports\BankValidatorInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
