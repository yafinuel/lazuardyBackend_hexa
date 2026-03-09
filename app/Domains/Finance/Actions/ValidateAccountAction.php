<?php

namespace App\Domains\Finance\Actions;

use App\Domains\Finance\Ports\BankValidatorInterface;

class ValidateAccountAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected BankValidatorInterface $validator)
    {}

    public function execute(string $bankCode, string $accountNumber)
    {
        return $this->validator->validateAccount($bankCode, $accountNumber);
    }
}
