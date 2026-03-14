<?php

namespace App\Domains\Authentication\Infrastructure\Messaging;

use App\Domains\Authentication\Infrastructure\Mail\OtpEmail;
use App\Domains\Authentication\Ports\MailerInterface;
use Illuminate\Support\Facades\Mail;

class LaravelMailer implements MailerInterface
{
    public function sendOtp(string $email, string $otp, string $subject, string $title): void {
        Mail::to($email)->send(new OtpEmail($otp, $subject, $title));
    }
}
