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
            unset($levels[CourseCategoryEnum::GENERAL->value]);

            $subjects = $this->subjectAction->execute($level);
            return [
                'levels' => $levels,
                'subjects' => $subjects,
            ];
        } else if ($category == CourseCategoryEnum::GENERAL->value) {
            $subjects = $this->subjectAction->execute(CourseCategoryEnum::GENERAL->value);
            return [
                'levels' => null,
                'subjects' => $subjects
            ];
        } else {
            return [
                'levels' => null,
                'subjects' => null
            ];
        }
    }
}
