<?php

namespace App\Domains\Authentication\Infrastructure\Messaging;

use App\Domains\Authentication\Ports\MailerInterface;
use App\Mail\OtpEmail;
use Illuminate\Support\Facades\Mail;

class LaravelMailer implements MailerInterface
{
    public function sendOtp($email, $otp): void {
        Mail::to($email)->send(new OtpEmail($otp));
    }
}
