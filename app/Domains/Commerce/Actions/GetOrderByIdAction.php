<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;

class GetOrderByIdAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(int $orderId)
    {
        $order = $this->repository->getOrderById($orderId);

        
    }
}