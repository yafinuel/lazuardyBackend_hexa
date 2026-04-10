<?php

namespace App\Domains\Notification\Actions;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;

class GetNotifByUserIdAction
{
    public function __construct(protected NotificationRepositoryInterface $repository) {}

    public function execute(int $userId, int $paginate = 15)
    {
        return $this->repository->getNotificationByUserId($userId, $paginate);
    }
}