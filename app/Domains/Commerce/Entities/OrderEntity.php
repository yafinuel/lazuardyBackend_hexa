<?php

namespace App\Domains\Commerce\Entities;

use App\Shared\Enums\OrderStatusEnum;

class OrderEntity
{
    public function __construct(
        public int $id,
        public ?int $userId = null,
        public ?string $orderNumber = null,
        public ?int $totalAmount = null,
        public ?OrderStatusEnum $status = null,
    ) {}
}