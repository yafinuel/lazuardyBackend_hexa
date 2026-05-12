<?php

namespace App\Domains\Commerce\Entities;

use App\Shared\Enums\PaymentStatusEnum;
use Carbon\Carbon;

class PaymentEntity
{
    public function __construct(
        public int $id,
        public ?int $orderId = null,
        public ?string $externalId = null,
        public ?string $paymentMethod = null,
        public ?string $paymentChannel  = null,
        public ?int $amount = null,
        public ?PaymentStatusEnum $status = null,
        public ?string $checkoutUrl = null,
        public ?Carbon $paidAt = null,
    ) {}
}