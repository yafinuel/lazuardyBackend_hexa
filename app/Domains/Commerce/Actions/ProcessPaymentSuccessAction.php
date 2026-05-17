<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Shared\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessPaymentSuccessAction
{
    public function __construct(protected CommerceRepositoryInterface $repository, protected CommerceServicePort $service) {}

    public function execute(array $payloadRaw): void
    {
        // Log::info('Processing payment callback with payloadRaw:', $payloadRaw);

        $data = [
            'status' => PaymentStatusEnum::COMPLETED,
            'payment_method' => $payloadRaw['payment_method'] ?? null,
            'paid_at' => isset($payloadRaw['paid_at']) ? Carbon::parse($payloadRaw['paid_at']) : null,
            'payment_channel' => $payloadRaw['payment_channel'] ?? null,
            'payload_raw' => json_encode($payloadRaw),
        ];

        $orderData = [
            'status' => PaymentStatusEnum::COMPLETED,
        ];

        $payment = $this->repository->updatePaymentByExternalId($payloadRaw['external_id'], $data);
        $order = $this->repository->updateOrder($payment->orderId, $orderData);
        $totalSession = $this->repository->getSessionCountFromOrder($order->id);
        
        $this->service->updateStudentSession($order->userId, $totalSession);
    }
}