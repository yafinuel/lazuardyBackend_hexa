<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Helpers\CommerceHelper;
use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Domains\Commerce\Ports\XenditBankPort;
use App\Shared\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\DB;

class CheckoutPackageAction
{
    public function __construct(
        protected CommerceRepositoryInterface $repository,
        protected CommerceServicePort $service,
        protected XenditBankPort $xendit,
        protected CommerceHelper $helper
    ) {}

    public function execute(int $userId, array $data): array
    {
        $orderNumber = $this->helper->createOrderNumber($userId, 'PACKAGE');
        $convertItems = $this->helper->convertPackageItemsToOrderItems($data['packages']);
        $totalPrice = $this->helper->calculateTotalPrice($data['packages']);
        $user = $this->service->getUserByIdAction($userId);

        DB::beginTransaction();
        try {
            $order = $this->repository->createOrder(
                userId: $userId,
                orderNumber: $orderNumber,
                amount: $totalPrice,
                status: PaymentStatusEnum::PENDING
            );

            $orderItems = $this->repository->createOrderItems(
                orderId: $order->id,
                items: $convertItems
            );

            $invoice = $this->xendit->createInvoice(
                externalId: $orderNumber,
                amount: $totalPrice,
                description: 'Pembayaran untuk order ' . $orderNumber,
                studentName: $user->name,
                studentEmail: $user->email,
            );

            $payment = $this->repository->createPayment(
                orderId: $order->id,
                externalId: $orderNumber,
                amount: $totalPrice,
                status: PaymentStatusEnum::PENDING,
                checkoutUrl: $invoice['invoice_url'],
                xenditId: $invoice['xendit_id']
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal melakukan checkout: ' . $e->getMessage(), 500);
        }
        return [
            'order_id' => $order->id,
            'checkout_url' => $invoice['invoice_url'],
        ];
    }
}