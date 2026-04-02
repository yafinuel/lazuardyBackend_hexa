<?php

namespace App\Domains\ClassDomain\Entities;

class ClassEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $level
    ) {}
}
