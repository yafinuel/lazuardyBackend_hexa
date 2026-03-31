<?php

namespace App\Domains\Package\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Package\Actions\GetPackagesAction;
use App\Domains\Package\Infrastructure\Delivery\Http\Resources\PackageResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(GetPackagesAction $action)
    {
        $result = $action->execute();
        
        return PackageResource::collection(
            $result['data']
        )->additional([
            'meta' => $result['meta']
        ]);
    }
}
