<?php

namespace App\Domains\UserProfile\User\Infrastructure\Delivery\Http\Controllers;

use App\Domains\UserProfile\User\Actions\UpdateProfilePictureAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfilePhoto(Request $request, UpdateProfilePictureAction $action)
    {
        $data = $request->validate([
            'profile_picture' => ['required', 'file', 'image'],
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
                'message' => 'Gagal memperbarui foto profil',
                'debug_error' => $e->getMessage(),
            ]);
        }
    }
}
