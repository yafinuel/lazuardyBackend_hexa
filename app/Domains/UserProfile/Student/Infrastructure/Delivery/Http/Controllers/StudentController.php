<?php

namespace App\Domains\UserProfile\Student\Infrastructure\Delivery\Http\Controllers;

use App\Domains\UserProfile\Student\Actions\StudentBiodataAction;
use App\Domains\UserProfile\Student\Actions\UpdateProfilePictureAction;
use App\Domains\UserProfile\Student\Actions\UpdateStudentProfileAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\GenderEnum;
use App\Shared\Enums\ReligionEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class StudentController extends Controller
{
    public function biodata(Request $request, StudentBiodataAction $action)
    {
        try {
            $user = $request->user();
            $data = $action->execute($user->id);
    
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Gagal mengambil biodata siswa',
                    'debug_error' => $e->getMessage(),
                ]);
        }
    }

    public function updateBiodata(Request $request, UpdateStudentProfileAction $action)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'date'],
            'gender' => ['sometimes', new Enum(GenderEnum::class)],
            'religion' => ['sometimes', new Enum(ReligionEnum::class)],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
            'class_id' => ['sometimes', 'integer', 'exists:classes,id'],
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
        ]);

        try {
            $user = $request->user();
            $action->execute($user->id, $data);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal memperbarui biodata siswa',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }
}
