<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\XenditBankPort;

class ValidateAccountAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected XenditBankPort $validator)
    {}

    public function execute(string $bankCode, string $accountNumber)
    {
        return $this->validator->validateAccount($bankCode, $accountNumber);
    }
}
