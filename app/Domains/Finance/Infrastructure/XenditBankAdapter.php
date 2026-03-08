<?php

namespace App\Domains\Finance\Infrastructure;

use App\Domains\Finance\Ports\BankValidatorInterface;
use Exception;
use Xendit\Configuration;
use Xendit\Payout\PayoutApi;

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
            $banks = $this->apiInstance->getPayoutChannels('IDR', 'BANK');
            $ewallet = $this->apiInstance->getPayoutChannels("IDR", "EWALLET");
            $allChannels = array_merge($banks, $ewallet);

            return collect($allChannels)->map(function ($channel) {
                return [
                    'bank_code' => $channel->getChannelCode(),
                    'bank_name' => $channel->getChannelName(),
                ];
            })->toArray();

        } catch (Exception $e) {
            throw new Exception("Gagal mengambil daftar bank: " . $e->getMessage());
        }
    }

    public function validateAccount(): array
    {
        throw new \Exception('Not implemented');
    }
}
