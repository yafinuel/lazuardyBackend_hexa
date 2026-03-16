<?php

namespace App\Domains\UserProfile\Tutor\Entities;

class TutorFileEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $cv,
        public ?string $certificate,
        public ?string $identityCard,
    )
    {}
}
