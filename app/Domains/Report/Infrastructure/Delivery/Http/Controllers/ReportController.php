<?php

namespace App\Domains\Report\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Report\Actions\CreateTutorReportAction;
use App\Domains\Report\Actions\GetAllReportsByStudentIdAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getAllReportsByStudentId(Request $request, GetAllReportsByStudentIdAction $action)
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

    public function createTutorReport(Request $request, CreateTutorReportAction $action)
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'tutor_id' => ['required', 'integer', 'exists:tutors,user_id'],
            'student_id' => ['required', 'integer', 'exists:students,user_id'],
            'topic' => ['required', 'string'],
            'notes' => ['required', 'string'],
        ]);

        $action->execute(
            $data['schedule_id'],
            $data['tutor_id'],
            $data['student_id'],
            $data['topic'],
            $data['notes']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Report created successfully'
        ]);
    }
}
    