<?php

namespace App\Domains\Finance\Infrastructure;

use App\Domains\Finance\Ports\BankValidatorInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Payout\PayoutApi;
use Xendit\Payout\CreatePayoutRequest;
use Xendit\XenditSdkException;

class XenditBankAdapter implements BankValidatorInterface
{
    protected PayoutApi $apiInstance;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->apiInstance = new PayoutApi();
    }

    public function getAvailableBanks(): array
    {
        try {
            $channels = $this->apiInstance->getPayoutChannels('IDR', 'BANK');

            return collect($channels)->map(function ($channel) {
                return [
                    'bank_code' => $channel->getChannelCode(),
                    'bank_name' => $channel->getChannelName(),
                ];
            })->values()->toArray();

        } catch (Exception $e) {
            Log::error("Xendit GetBanks Error: " . $e->getMessage());
            return [];
        }
    }

    public function validateAccount(string $bankCode, string $accountNumber): array
    {
        $createPayoutRequest = new CreatePayoutRequest([
            'reference_id' => 'val-' . time(),
            'channel_code' => $bankCode,
            'channel_properties' => [
                'account_number' => $accountNumber,
                'account_holder_name' => 'FETCH_HOLDER_NAME' // Placeholder wajib
            ],
            'amount' => 1,
            'currency' => 'IDR',
            'is_verification' => true 
        ]);

        try {
            $idempotencyKey = 'check-' . time();
            $result = $this->apiInstance->createPayout($idempotencyKey, null, $createPayoutRequest);
            
            $data = json_decode(json_encode($result->jsonSerialize()), true);
            
            $namaPemilik = $data['channel_properties']['account_holder_name'] ?? null;

            if (!$namaPemilik) {
                return [
                    'status' => 'error',
                    'message' => 'Nama pemilik tidak ditemukan'
                ];
            }

            return [
                'status' => 'success',
                'account_holder_name' => $namaPemilik
            ];

        } catch (XenditSdkException $e) {
            $error = json_decode(json_encode($e->getFullError()), true);
            Log::warning("Xendit Validation Fail", $error);
            
            return [
                'status' => 'error',
                'message' => $error['message'] ?? 'Gagal memverifikasi rekening',
                'error_code' => $error['error_code'] ?? 'API_ERROR'
            ];
        } catch (Exception $e) {
            Log::error("General Bank Validation Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ];
        }
    }
}