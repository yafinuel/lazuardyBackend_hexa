<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Domains\Commerce\Ports\XenditBankPort;
use App\Shared\Enums\PayoutStatusEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProcessApprovedPayoutAction
{
    public function __construct(protected CommerceRepositoryInterface $repository, protected CommerceServicePort $service, protected XenditBankPort $xendit) {}

    public function execute(int $adminId, int $payoutId)
    {
        $dbPayout = $this->repository->getPayoutById($payoutId);
        $tutor = $this->service->getTutorById($dbPayout->tutorId);
        
        if ($dbPayout->status !== PayoutStatusEnum::REQUESTED) {
            throw new ConflictHttpException("This payout request has already been processed.");
        }

        try {
            $xenditPayout = $this->xendit->createPayout(
                referenceId: $dbPayout->payoutNumber,
                amount: $dbPayout->amount,
                bankCode: $dbPayout->bankCode,
                accountHolderName: $dbPayout->accountHolderName,
                accountNumber: $dbPayout->accountNumber,
                userEmail: $tutor->email,
                description: "Payout for tutor {$tutor->name} with payout number {$dbPayout->payoutNumber}"
            );
            
            DB::beginTransaction();
            try {
                $this->repository->updatePayoutByPayoutNumber($dbPayout->payoutNumber, [
                    'status' => PayoutStatusEnum::PENDING,
                    'xendit_id' => $xenditPayout['xendit_id'],
                    'payload_raw' => $xenditPayout['payload_raw'],
                    'approved_by' => $adminId,
                    'approved_at' => now(),
                ]);
                
                DB::commit();
            } catch (Exception $dbException) {
                DB::rollBack();
                throw $dbException;
            }

        } catch (Exception $e) {
            Log::error("Failed to process payout ID {$payoutId} via Xendit: " . $e->getMessage());

            DB::beginTransaction();
            try {
                
                $this->repository->updatePayoutByPayoutNumber($dbPayout->payoutNumber, [
                    'status' => PayoutStatusEnum::FAILED,
                    'payload_raw' => json_encode(['error' => $e->getMessage()])
                ]);

                $rollbackSalary = $tutor->salary + $dbPayout->amount;
                $this->service->updateTutorById($dbPayout->tutorId, ['salary' => $rollbackSalary]);

                DB::commit();
            } catch (Exception $rollbackException) {
                DB::rollBack();
                Log::critical("FATAL: Failed to rollback tutor salary for payout ID {$payoutId}: " . $rollbackException->getMessage());
            }
            throw new Exception("Xendit Payout Failed: " . $e->getMessage());
        }
    }
}