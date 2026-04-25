<?php

namespace App\Domains\Report\Infrastructure\Delivery\Http\Controllers;

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
}
