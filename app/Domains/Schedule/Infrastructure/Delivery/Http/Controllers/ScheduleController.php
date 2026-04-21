<?php

namespace App\Domains\Schedule\Infrastructure\Delivery\Http\Controllers;

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
}
