<?php

namespace App\Domains\MailManager\Ports;

interface MailerInterface
{
    public function send(string $email, string $otp, string $subject, string $title): void;
}