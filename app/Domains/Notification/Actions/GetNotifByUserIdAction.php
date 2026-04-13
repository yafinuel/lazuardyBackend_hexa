<?php

namespace App\Domains\Notification\Actions;

use App\Domains\Notification\Entities\NotificationEntity;
use App\Domains\Notification\Ports\NotificationRepositoryInterface;

class GetNotifByUserIdAction
{
    public function __construct(protected NotificationRepositoryInterface $repository) {}

    public function execute(int $userId, int $paginate = 15)
    {
        $result = $this->repository->getNotificationByUserId($userId, $paginate);

        return $result->through(function ($notification) {
            return new NotificationEntity(
                id: $notification->id,
                type: $notification->type,
                notifiableType: $notification->notifiable_type,
                notifiableId: $notification->notifiable_id,
                title: $notification->data['title'],
                body: $notification->data['body'],
                data: $notification->data['data'],
                readAt: $notification->read_at,
                createdAt: $notification->created_at,
            );
        });
    }
}