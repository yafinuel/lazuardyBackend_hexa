<?php

namespace App\Domains\CourseCatalog\Ports;

interface CourseCatalogServicePort
{
    public function filterCategoryPage(string $category, string $level): array;
}
