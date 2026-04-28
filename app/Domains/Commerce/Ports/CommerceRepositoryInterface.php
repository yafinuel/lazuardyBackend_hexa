<?php

namespace App\Domains\Commerce\Ports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Shared\Enums\PaymentStatusEnum;
use Illuminate\Support\Collection;

interface CommerceRepositoryInterface
{
    public function createOrder(int $userId, string $orderNumber, int $amount, PaymentStatusEnum $status): Order;
    public function createPayment(int $orderId, string $externalId, int $amount, PaymentStatusEnum $status, string $checkoutUrl, string $xenditId): Payment;
    public function createOrderItems(int $orderId, array $items): Collection;
}