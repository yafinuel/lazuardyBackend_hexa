<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Shared\Enums\PaymentStatusEnum;

class ProcessPaymentExpiredAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(array $payloadRaw): void
    {
        $data = [
            'status' => PaymentStatusEnum::EXPIRED,
            'payload_raw' => json_encode($payloadRaw),
        ];

        $orderData = [
            'status' => PaymentStatusEnum::EXPIRED,
        ];

        $payment = $this->repository->updatePaymentByExternalId($payloadRaw['external_id'], $data);
        $this->repository->updateOrder($payment->orderId, $orderData);
    }
}