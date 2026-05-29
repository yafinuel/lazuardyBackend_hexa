<?php

namespace App\Domains\Package\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Package\Actions\GetPackageByIdAction;
use App\Domains\Package\Actions\GetPackagesAction;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function index(GetPackagesAction $action)
    {
        $result = $action->execute();
        
        return response()->json([
            'success' => 'success',
            'data' => $result,
        ]);
    }

    public function getPackageById(GetPackageByIdAction $action, int $id)
    {

        $result = $action->execute($id);

        return response()->json([
            'success' => 'success',
            'data' => $result,
        ]);
    }
}
