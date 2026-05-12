<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Shared\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HandlePaymentCallbackAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(array $payloadRaw): void
    {
        Log::info('Processing payment callback with payloadRaw:', $payloadRaw);

        $data = [
            'status' => PaymentStatusEnum::COMPLETED,
            'payment_method' => $payloadRaw['payment_method'] ?? null,
            'paid_at' => isset($payloadRaw['paid_at']) ? Carbon::parse($payloadRaw['paid_at']) : null,
            'payment_channel' => $payloadRaw['payment_channel'] ?? null,
            'payload_raw' => json_encode($payloadRaw),
        ];

        $this->repository->updatePaymentByExternalId($payloadRaw['external_id'], $data);
    }
}