<?php

namespace App\Domains\Subject\Entities;

class SubjectEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $icon_image_url,
        public ?int $classId,
        public ?string $className,
        public ?string $classLevel
    ) {}
}
