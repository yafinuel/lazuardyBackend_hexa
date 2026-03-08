<?php

namespace App\Domains\Finance\Ports;

interface BankValidatorInterface
{
    public function getAvailableBanks(): array;
    public function validateAccount(): array;
}
