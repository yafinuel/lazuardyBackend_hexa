<?php

namespace App\Domains\Notification\Infrastructure\Repository;

use App\Domains\Notification\Notifications\TemplateNotification;
use App\Domains\Notification\Ports\NotificationRepositoryInterface;
use App\Models\User;
use App\Models\UserFcmToken;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function getNotificationByUserId(int $userId, int $paginate): LengthAwarePaginator
    {
        $user = User::findOrFail($userId);
        return $user->notifications()->paginate($paginate);
    }

    public function pushNotificationToUser(int $userId, array $notificationData): void
    {
        $user = User::findOrFail($userId);
        $user->notify(new TemplateNotification($notificationData));
    }

    public function clearFcmToken(int $userId, ?string $deviceId = null): void
    {
        
        $query = UserFcmToken::where('user_id', $userId);

        if ($deviceId !== null) {
            $query->where('device_id', $deviceId);
        }

        $query->delete();
    }

    public function updateOrCreateFcmToken(int $userId, string $deviceId, string $token, ?string $platform = null): void
    {
        UserFcmToken::updateOrCreate(
            [
                'user_id' => $userId,
                'device_id' => $deviceId,
            ],
            [
                'fcm_token' => $token,
                'platform' => $platform,
                'last_seen_at' => Carbon::now(),
            ]
        );
    }
}
