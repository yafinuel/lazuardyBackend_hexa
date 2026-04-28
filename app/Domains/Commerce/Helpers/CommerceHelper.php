<?php

namespace App\Domains\Commerce\Helpers;

use App\Domains\Commerce\Infrastructure\Services\CommerceServiceAdapter;
use Carbon\Carbon;

class CommerceHelper
{
    public function __construct(
        protected CommerceServiceAdapter $service,
    ) {}
    
    public function createOrderNumber(int $userId, string $orderNumberType): string
    {
        return 'ORD_'. $orderNumberType . '_' . $userId . '_' . Carbon::now()->timestamp;
    }

    public function calculateSubTotalPackage(int $packageId, int $quantity): int
    {
        $package = $this->service->getPackageByIdAction($packageId);
        return $package->price * $quantity;
    }
    
    public function calculateTotalPrice(array $packages): int
    {
        $total = 0;

        foreach ($packages as $item) {
            $packageId = isset($item['id']) ? (int) $item['id'] : (isset($item['package_id']) ? (int) $item['package_id'] : null);
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : (isset($item['qty']) ? (int) $item['qty'] : 1);

            if (! $packageId || $quantity <= 0) {
                continue;
            }

            $total += $this->calculateSubTotalPackage($packageId, $quantity);
        }

        return $total;
    }

    public function convertPackageItemsToOrderItems(array $packages): array
    {
        $orderItems = [];

        foreach ($packages as $item) {
            $packageId = isset($item['id']) ? (int) $item['id'] : (isset($item['package_id']) ? (int) $item['package_id'] : null);
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : (isset($item['qty']) ? (int) $item['qty'] : 1);

            if (! $packageId || $quantity <= 0) {
                continue;
            }

            $price = $this->service->getPackageByIdAction($packageId)->price;
            $subTotal = $price * $quantity;

            $orderItems[] = [
                'package_id' => $packageId,
                'price' => $price,
                'qty' => $quantity,
                'subtotal' => $subTotal,
            ];
        }

        return $orderItems;
    }
}