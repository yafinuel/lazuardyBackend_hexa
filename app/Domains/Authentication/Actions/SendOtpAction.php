<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Ports\MailerInterface;
use App\Domains\Authentication\Ports\OtpRepositoryInterface;
use App\Shared\Enums\OtpIdentifierEnum;
use Carbon\Carbon;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendOtpAction
{
    protected int $length;
    protected int $expiryMinutes;
    /**
     * Create a new class instance.
     */
    public function __construct(protected OtpRepositoryInterface $repository, protected MailerInterface $mailer)
    {
        $length = 6;
        $expiryMinutes = 30;
    }

    public function execute(string $identifier, string $identifierType, string $verificationType)
    {
        $otp = str_pad(random_int(0, pow(10, $this->length) - 1), $this->length, '0', STR_PAD_LEFT);
        $expiredAt = Carbon::now()->addMinutes($this->expiryMinutes);

        try {
            $this->repository->storeOtp($identifier, $identifierType, $otp, $verificationType, $expiredAt);
    
            if($identifierType == OtpIdentifierEnum::EMAIL->value){
                $this->mailer->sendOtp($identifier, $otp);
            }
            return $otp;
        } catch (TransportExceptionInterface $e) {
            // Error koneksi ke Gmail (misal internet server mati atau diblokir Google)
            throw new \Exception("Gagal mengirim email. Pastikan koneksi internet stabil.");
        } catch (\Exception $e) {
            // Error umum lainnya (database error, dll)
            \Illuminate\Support\Facades\Log::error("OTP Error: " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan sistem saat memproses OTP.");
        }
    }
}
