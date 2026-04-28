<?php

namespace App\Domains\Commerce\Infrastructure\External;

use App\Domains\Commerce\Ports\XenditBankPort;
use Exception;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Payout\PayoutApi;
use Xendit\Payout\CreatePayoutRequest;
use Xendit\XenditSdkException;

class XenditBankAdapter implements XenditBankPort
{
    protected PayoutApi $apiInstance;
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->apiInstance = new PayoutApi();
        $this->invoiceApi = new InvoiceApi();
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

    public function createInvoice(
        string $externalId,
        int $amount,
        string $description,
        string $studentName,
        string $studentEmail,
        int $expiryDuration = 86400, // 24 jam
        ?string $successRedirectUrl = null,
        ?string $failureRedirectUrl = null
    ): array
    {
        $params = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => $description,
            'invoice_duration' => $expiryDuration,
            'customer' => [
                'given_name' => $studentName,
                'email' => $studentEmail
            ],
        ];

        if ($successRedirectUrl) {
            $params['success_redirect_url'] = $successRedirectUrl;
        }

        if ($failureRedirectUrl) {
            $params['failure_redirect_url'] = $failureRedirectUrl;
        }

        try {
            $createRequest = new CreateInvoiceRequest($params);
            $invoice = $this->invoiceApi->createInvoice($createRequest);

            return [
                'xendit_id' => $invoice->getId(),
                'invoice_url' => $invoice->getInvoiceUrl()
            ];
        } catch (XenditSdkException $e) {
            $error = json_decode(json_encode($e->getFullError()), true);
            Log::error('Xendit Invoice Create Failed', $error ?: ['message' => $e->getMessage()]);
            throw new Exception('Gagal membuat invoice: ' . ($error['message'] ?? $e->getMessage()), 500);
        } catch (Exception $e) {
            Log::error('General Invoice Create Error: ' . $e->getMessage());
            throw new Exception('Terjadi kesalahan sistem saat membuat invoice', 500);
        }
    }


}