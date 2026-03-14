<?php

namespace App\Domains\UserProfile\Student\Infrastructure\Delivery\Http\Controllers;

use App\Domains\UserProfile\Student\Actions\StudentBiodataAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function studentBiodata(Request $request, StudentBiodataAction $action)
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
}
