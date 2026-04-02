<?php

namespace App\Domains\CourseCatalog\Infrastructure\Delivery\Http\Controllers;

use App\Domains\CourseCatalog\Actions\GetClassLevelAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function getClassLevels(GetClassLevelAction $action)
    {
        $level = $action->execute();
        return response()->json(['level' => $level]);
    }
}
