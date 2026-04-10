<?php

namespace App\Domains\Dashboard\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Dashboard\Actions\StudentHomePageAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function studentHomepage(Request $request,StudentHomePageAction $action)
    {
        $request->validate([
            'notification_paginate' => ['required', 'integer'],
            'tutor_paginate' => ['required', 'integer']
        ]);
        $userId = $request->user()->id;
        $data = $action->execute($userId, $request['notification_paginate'], $request['tutor_paginate']);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
