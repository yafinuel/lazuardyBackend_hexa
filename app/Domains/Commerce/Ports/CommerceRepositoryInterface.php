<?php

namespace App\Domains\Commerce\Ports;

use App\Domains\Commerce\Entities\OrderEntity;
use App\Domains\Commerce\Entities\PaymentEntity;
use App\Domains\Commerce\Entities\PayoutEntity;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payout;
use App\Shared\Enums\OrderStatusEnum;
use App\Shared\Enums\PaymentStatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CommerceRepositoryInterface
{
    public function createOrder(int $userId, string $orderNumber, int $amount, OrderStatusEnum $status): Order;
    public function createPayment(int $orderId, string $externalId, int $amount, PaymentStatusEnum $status, string $checkoutUrl, string $xenditId): Payment;
    public function createPayout(array $data): Payout;
    public function getPayoutById(int $payoutId): PayoutEntity;
    public function createOrderItems(int $orderId, array $items): Collection;
    public function updatePaymentByExternalId(string $externalId, array $data): PaymentEntity;
    public function getOrderById(int $orderId): OrderEntity;
    public function updateOrder(int $orderId, array $data): OrderEntity;
    public function getSessionCountFromOrder(int $orderId): int;
    public function updatePayoutByPayoutNumber(string $payoutNumber, array $data): PayoutEntity;
    public function getPayoutByPayoutNumber(string $payoutNumber): ?PayoutEntity;
    public function getPayoutByXenditId(string $xenditId): ?PayoutEntity;
    public function getPaymentByExternalId(string $externalId): ?PaymentEntity;
    public function getPayoutByTutorId(int $tutorId, array $filters = [], int $page = 10): LengthAwarePaginator;
}