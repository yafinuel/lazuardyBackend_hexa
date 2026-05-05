<?php

namespace App\Domains\Tutor\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Tutor\Actions\GetTutorByCriteria;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Actions\GetTutorFileByUserIdAction;
use App\Domains\Tutor\Actions\UpdateTeachingProfileAction;
use App\Domains\Tutor\Actions\UpdateTutorProfileAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\GenderEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

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
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', new Enum(GenderEnum::class)],
            'date_of_birth' => ['required', 'date'],
            'telephone_number' => ['required', 'string', 'max:20'],
            'bank_code' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
        ]);

        $action->execute($request->user()->id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Biodata tutor berhasil diperbarui',
        ]);
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

    public function updateTeachingProfile(Request $request, UpdateTeachingProfileAction $action)
    {
        $data = $request->validate([
            'description' => ['nullable', 'string'],
            'learning_method' => ['nullable', 'array'],
            'schedules' => ['nullable', 'array'],
            'schedules.*.day' => ['required_with:schedules', 'string'],
            'schedules.*.time' => ['required_with:schedules', 'date_format:H:i'],
        ]);

        try {
            $action->execute($request->user()->id, $data);
            return response()->json([
                'status' => 'success',
                'message' => 'Profil mengajar berhasil diperbarui',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal memperbarui profil mengajar',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }
}
