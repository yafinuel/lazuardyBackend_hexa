<?php

namespace App\Domains\Tutor\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Actions\GetTutorFileByUserIdAction;
use App\Domains\Tutor\Actions\UpdateTutorProfileAction;
use App\Shared\Enums\GenderEnum;
use App\Shared\Enums\ReligionEnum;
use App\Shared\Enums\TutorStatusEnum;
use Illuminate\Validation\Rules\Enum;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    
    public function getTutorById(Request $request, GetTutorByIdAction $action)
    {
        try {
            $tutorId = $request->user()->id;
            $tutor = $action->execute($tutorId);
    
            return response()->json([
                'status' => 'success',
                'data' => $tutor,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal mengambil biodata tutor',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }

    public function updateBiodata(Request $request, UpdateTutorProfileAction $action)
    {
    }

    public function getTutorFileByUserId(Request $request, GetTutorFileByUserIdAction $action)
    {
        try {
            $tutorId = $request->user()->id;
            $files = $action->execute($tutorId);
    
            return response()->json([
                'status' => 'success',
                'data' => $files,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal mengambil file tutor',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }

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
