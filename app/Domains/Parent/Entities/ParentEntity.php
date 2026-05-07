<?php

namespace App\Domains\Parent\Entities;

class ParentEntity
{
    public function __construct(
        public int $id,
        public string $email,
        public int $studentId,
    ) {}
}