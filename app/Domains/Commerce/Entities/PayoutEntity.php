<?php

namespace App\Domains\Commerce\Entities;

use App\Shared\Enums\PayoutStatusEnum;

class PayoutEntity
{
    public function __construct(
        public int $id,
        public int $tutorId,
        public string $payoutNumber,
        public int $amount,
        public string $bankCode,
        public string $accountHolderName,
        public string $accountNumber,
        public PayoutStatusEnum $status
    ) {}
}