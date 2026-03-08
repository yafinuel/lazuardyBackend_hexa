<?php

namespace App\Domains\Finance\Actions;

use App\Domains\Finance\Ports\BankValidatorInterface;
use Illuminate\Support\Facades\Cache;

class GetBankListAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected BankValidatorInterface $validator) {}

    public function execute()
    {
        return Cache::remember('bank_list_xendit', 86400, function () {
            return $this->validator->getAvailableBanks();
        });
    }
}
