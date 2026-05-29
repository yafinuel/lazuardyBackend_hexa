<?php

namespace App\Domains\Commerce\Infrastructure\Repository;

use App\Domains\Commerce\Entities\OrderEntity;
use App\Domains\Commerce\Entities\OrderItemEntity;
use App\Domains\Commerce\Entities\PaymentEntity;
use App\Domains\Commerce\Entities\PayoutEntity;
use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payout;
use App\Shared\Enums\OrderStatusEnum;
use App\Shared\Enums\PaymentStatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EloquentCommerceRepository implements CommerceRepositoryInterface
{
    public function createOrder(int $userId, string $orderNumber, int $amount, OrderStatusEnum $status): Order
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
        $status = $payment->status instanceof PaymentStatusEnum ? $payment->status : PaymentStatusEnum::tryFrom($payment->status);

        return new PaymentEntity(
            id: $payment->id,
            orderId: $payment->order_id,
            externalId: $payment->external_id,
            paymentMethod: $payment->payment_method,
            paymentChannel: $payment->payment_channel,
            amount: $payment->amount,
            status: $status,
            checkoutUrl: $payment->checkout_url,
            paidAt: $payment->paid_at,
        );
    }

    public function getOrderById(int $orderId): OrderEntity
    {
        $order = Order::with('items')->findOrFail($orderId);

        $items = $order->items->map(function ($item) {
            return new OrderItemEntity(
                id: $item->id,
                orderId: $item->order_id,
                packageId: $item->package_id,
                qty: $item->qty,
                price: $item->price,
                subTotal: ($item->qty ?? 0) * ($item->price ?? 0),
                createdAt: $item->created_at,
                updatedAt: $item->updated_at,
            );
        })->toArray();

        $totalSessions = $this->getSessionCountFromOrder($orderId);

        return new OrderEntity(
            id: $order->id,
            userId: $order->user_id,
            orderNumber: $order->order_number,
            totalAmount: $order->total_amount,
            status: $order->status,
            items: $items,
            totalSessions: $totalSessions,
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

    public function updatePayoutByPayoutNumber(string $payoutNumber, array $data): PayoutEntity
    {
        $payout = Payout::where('payout_number', $payoutNumber)->firstOrFail();
        $payout->update($data);
        return new PayoutEntity(
            id: $payout->id,
            tutorId: $payout->tutor_id,
            payoutNumber: $payout->payout_number,
            amount: $payout->amount,
            bankCode: $payout->bank_code,
            accountHolderName: $payout->account_holder_name,
            accountNumber: $payout->account_number,
            status: $payout->status
        );
    }

        public function createPayout(array $data): Payout
    {
        return Payout::create($data);
    }

    public function getPayoutById(int $payoutId): PayoutEntity
    {
        $result = Payout::findOrFail($payoutId);
        
        return new PayoutEntity(
            id: $result->id,
            tutorId: $result->tutor_id,
            payoutNumber: $result->payout_number,
            amount: $result->amount,
            bankCode: $result->bank_code,
            accountHolderName: $result->account_holder_name,
            accountNumber: $result->account_number,
            status: $result->status
        );
    }

    public function getPayoutByPayoutNumber(string $payoutNumber): ?PayoutEntity
    {

        $result = Payout::where('payout_number', $payoutNumber)->first();
        
        if (!$result) {
            return null;
        }

        return new PayoutEntity(
            id: $result->id,
            tutorId: $result->tutor_id,
            payoutNumber: $result->payout_number,
            amount: $result->amount,
            bankCode: $result->bank_code,
            accountHolderName: $result->account_holder_name,
            accountNumber: $result->account_number,
            status: $result->status
        );
    }

    public function getPayoutByXenditId(string $xenditId): ?PayoutEntity
    {
        $result = Payout::where('xendit_id', $xenditId)->first();

        if (!$result) {
            return null;
        }

        return new PayoutEntity(
            id: $result->id,
            tutorId: $result->tutor_id,
            payoutNumber: $result->payout_number,
            amount: $result->amount,
            bankCode: $result->bank_code,
            accountHolderName: $result->account_holder_name,
            accountNumber: $result->account_number,
            status: $result->status
        );
    }

    public function getPaymentByExternalId(string $externalId): ?PaymentEntity
    {
        $payment = Payment::where('external_id', $externalId)->first();
        Log::info("Payment found: " . ($payment ? $payment->id : 'No payment found for external_id: ' . $externalId));
        
        if (!$payment) {
            return null;
        }

        $status = $payment->status instanceof PaymentStatusEnum ? $payment->status : PaymentStatusEnum::tryFrom($payment->status);

        return new PaymentEntity(
            id: $payment->id,
            orderId: $payment->order_id,
            externalId: $payment->external_id,
            paymentMethod: $payment->payment_method,
            paymentChannel: $payment->payment_channel,
            amount: $payment->amount,
            status: $status,
            checkoutUrl: $payment->checkout_url,
            paidAt: $payment->paid_at,
        );
    }

    public function getPayoutByTutorId(int $tutorId, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Payout::where('tutor_id', $tutorId);

        if (isset($filters['status'])) {
            $statuses = is_array($filters['status']) ? $filters['status'] : [$filters['status']];
            $query->whereIn('status', $statuses);
        }

        $payouts = $query->paginate($perPage);

        return $payouts->through(function ($payout) {
            return new PayoutEntity(
                id: $payout->id,
                tutorId: $payout->tutor_id,
                payoutNumber: $payout->payout_number,
                amount: $payout->amount,
                bankCode: $payout->bank_code,
                accountHolderName: $payout->account_holder_name,
                accountNumber: $payout->account_number,
                status: $payout->status
            );
        });
    }
}