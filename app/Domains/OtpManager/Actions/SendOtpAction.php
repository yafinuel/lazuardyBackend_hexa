<?php

namespace App\Domains\OtpManager\Actions;

use App\Domains\MailManager\Ports\MailerInterface;
use App\Domains\OtpManager\Ports\OtpRepositoryInterface;
use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;
use Carbon\Carbon;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendOtpAction
{
    protected int $length;
    protected int $expiryMinutes;
    
    public function __construct(protected OtpRepositoryInterface $repository, protected MailerInterface $mailer)
    {
        $this->length = 4;
        $this->expiryMinutes = 30;
    }

    public function execute(string $identifier, OtpIdentifierEnum $identifierType, OtpVerificationTypeEnum $verificationType, string $subject, string $title): string
    {
        $code = str_pad(random_int(0, pow(10, $this->length) - 1), $this->length, '0', STR_PAD_LEFT);
        $expiredAt = Carbon::now()->addMinutes($this->expiryMinutes);

        try {
            $otp = $this->repository->storeOtp($identifier, $identifierType, $code, $verificationType, $expiredAt);
            
            if ($identifierType === OtpIdentifierEnum::EMAIL) {
                $this->mailer->send($identifier, $code, $subject, $title);
            }
            return $code;
        } catch (TransportExceptionInterface $e) {
            // Error koneksi ke Gmail (misal internet server mati atau diblokir Google)
            throw new \Exception("Gagal mengirim email. Pastikan koneksi internet stabil.");
        } catch (\Exception $e) {
            // Error umum lainnya (database error, dll)
            \Illuminate\Support\Facades\Log::error("OTP Error: " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan sistem saat memproses OTP.");
        }
        // return ;
    }
}