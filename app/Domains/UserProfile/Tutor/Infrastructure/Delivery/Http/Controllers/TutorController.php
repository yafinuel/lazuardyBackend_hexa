<?php

namespace App\Domains\UserProfile\Tutor\Infrastructure\Delivery\Http\Controllers;

use App\Domains\UserProfile\Tutor\Actions\GetTutorFileAction;
use App\Domains\UserProfile\Tutor\Actions\TutorBiodataAction;
use App\Domains\UserProfile\Tutor\Actions\UpdateTutorProfileAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\GenderEnum;
use App\Shared\Enums\ReligionEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class TutorController extends Controller
{
    public function biodata(Request $request, TutorBiodataAction $action)
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
            'name' => ['sometimes', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'date'],
            'gender' => ['sometimes', new Enum(GenderEnum::class)],
            'religion' => ['sometimes', new Enum(ReligionEnum::class)],
            'telephone_number' => ['sometimes', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],

            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],

            'education' => ['sometimes', 'array'],
            'description' => ['sometimes', 'string'],
            'bankCode' => ['sometimes', 'string'],
            'accountNumber' => ['sometimes', 'string'],
            'learningMethod' => ['sometimes', 'array'],
            'status' => ['sometimes', 'string'],
        ]);
        try {
            $action->execute($request->user()->id, $data);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal memperbarui biodata tutor',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }

    public function getTutorFile(Request $request, GetTutorFileAction $action)
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
}
