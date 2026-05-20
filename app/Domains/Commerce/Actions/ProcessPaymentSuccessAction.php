<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Shared\Enums\OrderStatusEnum;
use App\Shared\Enums\PaymentStatusEnum;
use Carbon\Carbon;

class ProcessPaymentSuccessAction
{
    public function __construct(protected CommerceRepositoryInterface $repository, protected CommerceServicePort $service) {}

    public function execute(array $payloadRaw): void
    {
        $payment = $this->repository->getPaymentByExternalId($payloadRaw['external_id']);

        if (!$payment) {
            return;
        } elseif ($payment->status !== PaymentStatusEnum::PENDING) {
            return;
        }

        $data = [
            'status' => PaymentStatusEnum::PAID,
            'payment_method' => $payloadRaw['payment_method'] ?? null,
            'paid_at' => isset($payloadRaw['paid_at']) ? Carbon::parse($payloadRaw['paid_at']) : null,
            'payment_channel' => $payloadRaw['payment_channel'] ?? null,
            'payload_raw' => json_encode($payloadRaw),
        ];

        $orderData = [
            'status' => OrderStatusEnum::COMPLETED,
        ];

        $payment = $this->repository->updatePaymentByExternalId($payloadRaw['external_id'], $data);
        $order = $this->repository->updateOrder($payment->orderId, $orderData);
        $totalSession = $this->repository->getSessionCountFromOrder($order->id);
        
        $this->service->updateStudentSession($order->userId, $totalSession);
    }
}