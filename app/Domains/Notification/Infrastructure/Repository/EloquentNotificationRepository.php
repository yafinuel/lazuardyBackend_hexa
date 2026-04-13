<?php

namespace App\Domains\Notification\Infrastructure\Repository;

use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): LengthAwarePaginator
    {
        $user = User::findOrFail($userId);
        return $user->notifications()->paginate($paginate);
    }
}
