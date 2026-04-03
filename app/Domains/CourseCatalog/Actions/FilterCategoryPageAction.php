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
        $level = $level ?? 'sd';
        $data = $this->service->filterCategoryPage(strtolower($category), strtolower($level));
        if ($data['levels'] != null) {
            $level = array_map(function ($item) {
                return strtoupper($item);
            }, $data['levels']);
            $data['levels'] = $level;
        }
        return $data;
    }
}
