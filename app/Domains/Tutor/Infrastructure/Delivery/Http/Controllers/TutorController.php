<?php

namespace App\Domains\Tutor\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function getTutorByCriteria(Request $request, GetTutorByCriteria $action)
    {
        $filters = $request->only(['subject', 'class_name', 'level']);
        
        $tutors = $action->execute($filters);

        return response()->json([
            'status' => 'success',
            'data' => $tutors,
        ]);
    }
}
