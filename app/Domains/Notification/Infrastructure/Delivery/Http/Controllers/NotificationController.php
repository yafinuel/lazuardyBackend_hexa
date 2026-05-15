<?php

namespace App\Domains\Notification\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Notification\Actions\ClearFcmTokenAction;
use App\Domains\Notification\Actions\GetNotifByUserIdAction;
use App\Domains\Notification\Actions\UpdateOrCreateFcmTokenAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotificationByUserId(Request $request, GetNotifByUserIdAction $action)
    {
        $userId = $request->user()->id;

        $data = $action->execute($userId);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    
    public function updateFcmToken(Request $request, UpdateOrCreateFcmTokenAction $action)
    {
        $data = $request->validate([
            'fcm_token' => 'required|string|max:2048',
            'device_id' => 'required|string|max:255',
            'platform' => 'nullable|string|max:50',
        ]);

        $userId = $request->user()->id;

        $action->execute($userId, $data['device_id'], $data['fcm_token'], $data['platform'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated successfully',
        ]);
    }

    public function clearFcmToken(Request $request, ClearFcmTokenAction $action)
    {
        $data = $request->validate([
            'device_id' => 'required|string|max:255',
        ]);
        $userId = $request->user()->id;

        $action->execute($userId, $data['device_id']);

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token cleared successfully',
        ]);
    }
}
