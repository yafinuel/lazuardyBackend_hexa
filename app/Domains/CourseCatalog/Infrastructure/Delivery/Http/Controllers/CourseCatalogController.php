<?php

namespace App\Domains\CourseCatalog\Infrastructure\Delivery\Http\Controllers;

use App\Domains\CourseCatalog\Actions\FilterCategoryPageAction;
use App\Http\Controllers\Controller;
use App\Shared\Enums\CourseCategoryEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class CourseCatalogController extends Controller
{
    public function filterCategoryPageAction(Request $request, FilterCategoryPageAction $action)
    {
        $filters = $request->validate([
            'category' => ['nullable', new Enum(CourseCategoryEnum::class)],
            'level' => ['nullable', 'string'],
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $action->execute($filters['category'] ?? null, $filters['level'] ?? null)
        ]);
    }
}
