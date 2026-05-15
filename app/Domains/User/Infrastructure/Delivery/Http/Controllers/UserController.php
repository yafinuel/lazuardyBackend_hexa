<?php

namespace App\Domains\User\Infrastructure\Delivery\Http\Controllers;

use App\Domains\User\Actions\UpdatePhotoProfileAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfilePhoto(Request $request, UpdatePhotoProfileAction $action)
    {
        $data = $request->validate([
            'profile_photo' => 'required|image|max:2048',
        ]);

        $userId = $request->user()->id;

        $action->execute($userId, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated successfully'
        ]);
    }
}
