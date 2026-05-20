<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Shared\Enums\OrderStatusEnum;
use App\Shared\Enums\PaymentStatusEnum;

class ProcessPaymentExpiredAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(array $payloadRaw): void
    {
        $payment = $this->repository->getPaymentByExternalId($payloadRaw['external_id']);

        if (!$payment) {
            return;
        } elseif ($payment->status !== PaymentStatusEnum::PENDING) {
            return;
        }

        $data = [
            'status' => PaymentStatusEnum::EXPIRED,
            'payload_raw' => json_encode($payloadRaw),
        ];

        $orderData = [
            'status' => OrderStatusEnum::CANCELLED,
        ];

        $payment = $this->repository->updatePaymentByExternalId($payloadRaw['external_id'], $data);
        $this->repository->updateOrder($payment->orderId, $orderData);
    }
}