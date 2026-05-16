<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Helpers\CommerceHelper;
use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Shared\Enums\PaymentStatusEnum;
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

        if($tutor->sarary < $data['amount']){
            throw new ValidationException('Insufficient balance for payout request.');
        }

        $payoutNumber = $this->helper->createPayoutNumber($tutorId);

        DB::beginTransaction();
        try {
            $decreaseSalary = $tutor->sarary - $data['amount'];

            $order = $this->repository->createOrder(
                userId: $tutorId,
                orderNumber: $payoutNumber,
                amount: $data['amount'],
                status: PaymentStatusEnum::PENDING
            );

            $payment = $this->repository->createPayment(
                orderId: $order->id,
                externalId: $payoutNumber,
                amount: $data['amount'],
                status: PaymentStatusEnum::PENDING,
                checkoutUrl: '',
                xenditId: ''
            );

            $this->service->updateTutorById($tutorId, ['salary' => $decreaseSalary]);

            DB::commit();

            // return $payout;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}