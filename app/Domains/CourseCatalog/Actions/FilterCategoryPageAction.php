<?php

namespace App\Domains\CourseCatalog\Actions;

use App\Domains\CourseCatalog\Ports\CourseCatalogServicePort;

class FilterCategoryPageAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CourseCatalogServicePort $service) {}

    public function execute(?string $category, ?string $level)
    {
        $level = $level ?? 'SD';
        return $this->service->filterCategoryPage($category, $level);
    }
}
