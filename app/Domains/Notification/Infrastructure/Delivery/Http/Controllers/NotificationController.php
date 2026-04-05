<?php

namespace App\Domains\Notification\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Notification\Actions\GetAllUserNotificationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAllUserNotification(Request $request, GetAllUserNotificationAction $action)
    {
        $userId = $request->user()->id;

        $data = $action->execute($userId);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
