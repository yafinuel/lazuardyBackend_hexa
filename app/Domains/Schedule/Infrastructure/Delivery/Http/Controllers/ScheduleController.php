<?php

namespace App\Domains\Schedule\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Schedule\Actions\CancelScheduleAction;
use App\Domains\Schedule\Actions\CreateMeetingScheduleAction;
use App\Domains\Schedule\Actions\GetScheduleByIdAction;
use App\Domains\Schedule\Actions\GetTutorSchedulesByDayAction;
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

    public function createMeetingSchedule(Request $request, CreateMeetingScheduleAction $action)
    {
        $data = $request->validate([
            'tutor_id' => ['required', 'integer', 'exists:tutors,user_id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i:s'],
            'learning_method' => ['required', 'in:online,offline'],
            'address' => ['required', 'string', 'max:255'],
        ]);
        
        $data['student_id'] = $request->user()->id;

        $action->execute($data);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function getTutorSchedulesByDay(Request $request, GetTutorSchedulesByDayAction $action)
    {
        $data = $request->validate([
            'tutor_id' => ['required', 'integer', 'exists:tutors,user_id'],
            'day' => ['nullable', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
        ]);

        $result = $action->execute($data['tutor_id'], $data['day'] ?? null);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
