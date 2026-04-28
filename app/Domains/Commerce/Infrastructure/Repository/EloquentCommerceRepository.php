<?php

namespace App\Domains\Commerce\Infrastructure\Repository;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Shared\Enums\PaymentStatusEnum;
use Illuminate\Support\Collection;

class EloquentCommerceRepository implements CommerceRepositoryInterface
{
    public function createOrder(int $userId, string $orderNumber, int $amount, PaymentStatusEnum $status): Order
    {
        return Order::create([
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'total_amount' => $amount,
            'status' => $status,
        ]);
    }

    public function createPayment(int $orderId, string $externalId, int $amount, PaymentStatusEnum $status, string $checkoutUrl, string $xenditId): Payment
    {
        return Payment::create([
            'order_id' => $orderId,
            'external_id' => $externalId,
            'amount' => $amount,
            'status' => $status,
            'checkout_url' => $checkoutUrl,
            'xendit_id' => $xenditId, // Assuming externalId is the same as xendit_id for simplicity
        ]);
    }

    public function createOrderItems(int $orderId, array $items): Collection
    {
        $order = Order::findOrFail($orderId);

        return $order->items()->createMany($items);
    }
}