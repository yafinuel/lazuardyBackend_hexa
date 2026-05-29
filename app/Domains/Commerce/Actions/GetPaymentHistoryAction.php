<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Ports\CommerceRepositoryInterface;

class GetPaymentHistoryAction
{
    public function __construct(protected CommerceRepositoryInterface $repository) {}

    public function execute(int $userid, int $perPage = 10, int $page = 1)
    {
        return $this->repository->getPaymentByStudentId($userid, $perPage, $page);
    }
}