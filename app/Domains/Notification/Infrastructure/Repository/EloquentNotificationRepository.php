<?php

namespace App\Domains\Notification\Infrastructure\Repository;

use App\Domains\Notification\Entities\NotificationEntity;
use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Models\User;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): array
    {
        $user = User::findOrFail($userId);
        $paginator = $user->notifications()->paginate($paginate);
        $notifications = collect($paginator->items())->map(function (object $notification) {
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
        })->toArray();

        return [
            'data' => $notifications,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
}
