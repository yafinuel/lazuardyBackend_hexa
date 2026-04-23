<?php

namespace App\Domains\Student\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Student\Actions\UpdateStudentBiodataAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\GenderEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class StudentController extends Controller
{
    public function meStudent(Request $request, GetStudentByIdAction $action)
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

    public function updateBiodata(Request $request, UpdateStudentBiodataAction $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'gender' => ['required', new Enum(GenderEnum::class)],
            'date_of_birth' => ['required', 'date'],
            'telephone_number' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string'],
            'regency' => ['required', 'string'],
            'district' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
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
