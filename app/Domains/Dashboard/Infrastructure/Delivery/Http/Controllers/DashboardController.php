<?php

namespace App\Domains\Dashboard\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Dashboard\Actions\StudentHomePageAction;
use App\Domains\Dashboard\Actions\StudentProfilePageAction;
use App\Domains\Dashboard\Actions\StudentSchedulePageAction;
use App\Domains\Dashboard\Actions\TutorHomePageAction;
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

    public function studentSchedulePage(Request $request, StudentSchedulePageAction $action)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'paginate' => ['nullable', 'integer']
        ]);
        $userId = $request->user()->id;

        $result = $action->execute($userId, $data);
        
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function tutorHomePage(Request $request, TutorHomePageAction $action)
    {
        $data = $request->validate([
            'notification_paginate' => ['nullable', 'integer']
        ]);
        $userId = $request->user()->id;
        if (!isset($data['notification_paginate'])) {
            $data['notification_paginate'] = 2;
        }
        $data = $action->execute($userId, $data['notification_paginate']);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
