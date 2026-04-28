<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\XenditBankPort;
use Illuminate\Support\Facades\Cache;

class GetBankListAction
{
    public function __construct(protected XenditBankPort $validator) {}

    public function execute()
    {
        return Cache::remember('bank_list_xendit', 86400, function () {
            return $this->validator->getAvailableBanks();
        });
    }
}
