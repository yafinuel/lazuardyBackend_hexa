<?php

namespace App\Domains\MailManager\Infrastructure\Messaging;

use App\Domains\MailManager\Infrastructure\Mail\OtpEmail;
use App\Domains\MailManager\Ports\MailerInterface;
use Illuminate\Support\Facades\Mail;

class LaravelMailer implements MailerInterface
{
    public function send(string $email, string $otp, string $subject, string $title): void
    {
        Mail::to($email)->queue(new OtpEmail($otp, $subject, $title));
    }
}