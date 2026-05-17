<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Helpers\CommerceHelper;
use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Shared\Enums\PayoutStatusEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class RequestPayoutAction
{
    public function __construct(
        protected CommerceRepositoryInterface $repository,
        protected CommerceServicePort $service,
        protected CommerceHelper $helper
    ) {}

    public function execute(int $tutorId, array $data)
    {
        $tutor = $this->service->getTutorById($tutorId);

        if($tutor->salary < $data['amount']){
            throw new ValidationException('Insufficient balance for payout request.');
        }

        $decreasedSalary = $tutor->salary - $data['amount'];

        $payoutNumber = $this->helper->createPayoutNumber($tutorId);

        $payoutData = [
            'tutor_id' => $tutorId,
            'payout_number' => $payoutNumber,
            'amount' => $data['amount'],
            'bank_code' => $tutor->bankCode,
            'account_holder_name' => $tutor->accountHolderName,
            'account_number' => $tutor->accountNumber,
            'status' => PayoutStatusEnum::REQUESTED,
        ];

        DB::beginTransaction();
        try {
            $this->repository->createPayout($payoutData);
            $this->service->updateTutorById($tutorId, ['salary' => $decreasedSalary]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}