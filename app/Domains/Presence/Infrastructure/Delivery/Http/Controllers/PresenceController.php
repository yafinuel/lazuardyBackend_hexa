<?php

namespace App\Domains\Presence\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Presence\Actions\CreatePresenceAction;
use App\Domains\Presence\Actions\GetPresenceByUserId;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function getPresenceByUserId(Request $request, GetPresenceByUserId $action)
    {
        $data = $request->validate([
            'paginate' => ['nullable', 'integer']
        ]);
        $userId = $request->user()->id;
        $result = $action->execute($userId, $data);
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function createPresence(Request $request, CreatePresenceAction $action)
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'topic' => ['required', 'string'],
            'notes' => ['required', 'string'],
        ]);

        $tutorId = $request->user()->id;

        $action->execute(
            $data['schedule_id'],
            $tutorId,
            $data['topic'],
            $data['notes']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Report created successfully'
        ]);
    }
}
    