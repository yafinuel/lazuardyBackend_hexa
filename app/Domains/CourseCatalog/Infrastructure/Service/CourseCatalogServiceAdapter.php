<?php

namespace App\Domains\CourseCatalog\Infrastructure\Service;

use App\Domains\ClassDomain\Actions\GetClassLevelAction;
use App\Domains\CourseCatalog\Ports\CourseCatalogServicePort;
use App\Domains\Subject\Actions\GetSubjectByLevelAction;
use App\Shared\Enums\CourseCategoryEnum;

class CourseCatalogServiceAdapter implements CourseCatalogServicePort
{
    public function __construct(protected GetClassLevelAction $levelAction, protected GetSubjectByLevelAction $subjectAction) {}

    public function filterCategoryPage(?string $category, string $level): array
    {
        if ($category == CourseCategoryEnum::ACADEMIC->value){
            $levels = $this->levelAction->execute();
            $levels = collect($levels)->filter(function ($value) {
                return $value !== CourseCategoryEnum::GENERAL->value;
            })->values()->toArray();
            $subjects = $this->subjectAction->execute($level, CourseCategoryEnum::ACADEMIC->value);
        } else if ($category == CourseCategoryEnum::GENERAL->value) {
            $levels = null;
            $subjects = $this->subjectAction->execute(CourseCategoryEnum::GENERAL->value);
        } else {
            $levels = null;
            $subjects = null;
        }
            
        return [
            'levels' => $levels,
            'subjects' => $subjects
        ];
    }
}
