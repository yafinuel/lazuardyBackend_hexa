<?php

namespace App\Domains\Subject\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Subject\Actions\GetAllSubjectAction;
use App\Domains\Subject\Actions\GetSubjectByClassAction;
use App\Domains\Subject\Actions\GetSubjectByLevelAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getAllSubjects(GetAllSubjectAction $action)
    {
        $subjects = $action->execute();

        return response()->json([
            'success' => 'success',
            'data' => $subjects['data'],
            'meta' => $subjects['meta'],
        ]);
    }

    public function getSubjectByClass(Request $request, GetSubjectByClassAction $action)
    {
        $classId = $request->input('classId');
        $subjects = $action->execute($classId);

        return response()->json([
            'success' => 'success',
            'data' => $subjects['data'],
            'meta' => $subjects['meta'],
        ]);
    }

    public function getSubjectByLevel(Request $request, GetSubjectByLevelAction $action)
    {
        $level = $request->input('level');
        $subjects = $action->execute($level);

        return response()->json([
            'success' => 'success',
            'data' => $subjects['data'],
            'meta' => $subjects['meta'],
        ]);
    }
}
