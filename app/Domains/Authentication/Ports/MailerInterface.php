<?php

namespace App\Domains\Authentication\Ports;

interface MailerInterface
{
    public function sendOtp(string $email, string $otp): void;
}
