<?php

namespace App\Domains\Notification\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Notification\Actions\GetNotifByUserIdAction;
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
}
