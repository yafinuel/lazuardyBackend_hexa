<?php

namespace App\Domains\Schedule\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Schedule\Actions\CancelScheduleAction;
use App\Domains\Schedule\Actions\GetScheduleByIdAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getScheduleById(Request $request, GetScheduleByIdAction $action)
    {
        $data = $request->validate([
            'schedule_id' => 'required|integer',
        ]);
        $result = $action->execute($data['schedule_id']);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function cancelSchedule(Request $request, CancelScheduleAction $action)
    {
        $data = $request->validate([
            'schedule_id' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);
        $userId = $request->user()->id;
        $action->execute($userId, $data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Schedule cancelled successfully',
        ]);
    }
}
