<?php

namespace App\Domains\ClassDomain\Ports;

use Illuminate\Support\Collection;

interface ClassRepositoryInterface
{
    public function getClassLevels(): Collection;
    public function getAllClasses(): array;
    public function getClassesByLevel(string $level): array;
}
