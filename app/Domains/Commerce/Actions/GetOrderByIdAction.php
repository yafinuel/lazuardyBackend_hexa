<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;
use Illuminate\Validation\UnauthorizedException;

class GetOrderByIdAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(int $userId, int $orderId)
    {
        $order = $this->repository->getOrderById($orderId);
        if ($order->userId !== $userId) {
            throw new UnauthorizedException('You are not authorized to access this order.');
        }
        return $order;
    }
}