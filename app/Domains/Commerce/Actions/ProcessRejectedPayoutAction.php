<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Shared\Enums\PayoutStatusEnum;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProcessRejectedPayoutAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(int $adminId, int $payoutId, string $reason)
    {
        $dbPayout = $this->repository->getPayoutById($payoutId);

        if ($dbPayout->status !== PayoutStatusEnum::REQUESTED) {
            throw new ConflictHttpException("This payout request has already been processed.");
        }

        $this->repository->updatePayoutByPayoutNumber($dbPayout->payoutNumber, [
            'status' => PayoutStatusEnum::REJECTED,
            'payload_raw' => json_encode(['rejection_reason' => $reason]),
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);
    }
}