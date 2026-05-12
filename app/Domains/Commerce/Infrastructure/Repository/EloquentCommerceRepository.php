<?php

namespace App\Domains\Commerce\Infrastructure\Repository;

use App\Domains\Commerce\Entities\OrderEntity;
use App\Domains\Commerce\Entities\PaymentEntity;
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

    public function updatePaymentByExternalId(string $externalId, array $data): PaymentEntity
    {
        $payment = Payment::where('external_id',$externalId)->firstOrFail();
        $payment->update($data);
        return new PaymentEntity(
            id: $payment->id,
            orderId: $payment->order_id,
            externalId: $payment->external_id,
            paymentMethod: $payment->payment_method,
            paymentChannel: $payment->payment_channel,
            amount: $payment->amount,
            status: $payment->status,
            checkoutUrl: $payment->checkout_url,
            paidAt: $payment->paid_at,
        );
    }

    public function getOrderById(int $orderId): OrderEntity
    {
        $order = Order::findOrFail($orderId);
        return new OrderEntity(
            id: $order->id,
            userId: $order->user_id,
            orderNumber: $order->order_number,
            totalAmount: $order->total_amount,
            status: $order->status,
        );
    }

    public function updateOrder(int $orderId, array $data): OrderEntity
    {
        $order = Order::findOrFail($orderId);
        $order->update($data);
        return new OrderEntity(
            id: $order->id,
            userId: $order->user_id,
            orderNumber: $order->order_number,
            totalAmount: $order->total_amount,
            status: $order->status,
        );
    }

    public function getOrderItemsByOrderId(int $orderId): Collection
    {
        $order = Order::findOrFail($orderId);
        return $order->items;
    }

    public function getSessionCountFromOrder(int $orderId): int
    {
        $order = Order::with('items.package')->findOrFail($orderId);

        return $order->items->sum(function ($item) {
            return $item->qty * ($item->package->session ?? 0);
        });
    }
}