<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Shared\Enums\PayoutStatusEnum;
use Illuminate\Support\Facades\Log;

class CallbackFailedHandlePayoutAction
{
    public function __construct(
        protected CommerceRepositoryInterface $repository,
        protected CommerceServicePort $service,
    ) {}

    public function execute(array $payloadRaw): void
    {
        $ref = $payloadRaw['reference_id'] ?? $payloadRaw['external_id'] ?? $payloadRaw['id'] ?? null;

        if (!$ref) {
            Log::error('Missing payout reference in failed payout callback. Payload keys: ' . json_encode(array_keys($payloadRaw)));
            return;
        }

        $payout = $this->repository->getPayoutByPayoutNumber($ref);

        if (!$payout) {
            // fallback: maybe payload contains xendit disbursement id instead of our reference
            $payout = $this->repository->getPayoutByXenditId($ref);
        }

        if (!$payout) {
            Log::error("Payout with number or xendit_id " . $ref . " not found. Cannot process callback.");
            return;
        }

        if ($payout->status === PayoutStatusEnum::SUCCESS) {
            Log::warning("Payout with number " . $ref . " has already been marked as SUCCESS. Ignoring duplicate callback.");
            return;
        }

        if ($payout->status === PayoutStatusEnum::FAILED) {
            Log::warning("Payout with number " . $ref . " was previously marked as FAILED. Manual review may be required.");
            return;
        }

        $data = [
            'status' => PayoutStatusEnum::FAILED,
            'failure_code' => $payloadRaw['failure_code'] ?? null,
            'payload_raw' => $payloadRaw,
        ];

        $payout = $this->repository->updatePayoutByPayoutNumber($ref, $data);

        $tutor = $this->service->getTutorById($payout->tutorId);

        $salaryRefund = $tutor->salary + $payout->amount;
        
        $this->service->updateTutorById(
            $payout->tutorId, 
            ['salary' => $salaryRefund]
        );
    }
}