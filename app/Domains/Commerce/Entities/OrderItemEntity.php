<?php

namespace App\Domains\Commerce\Entities;

use Carbon\Carbon;

class OrderItemEntity
{
    public function __construct(
        public ?int $id,
        public ?int $orderId,
        public ?int $packageId,
        public ?int $qty,
        public ?int $price,
        public ?int $subTotal = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
    ) {}
}