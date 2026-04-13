<?php

namespace App\Domains\ClassDomain\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClassRepositoryInterface
{
    public function getClassLevels(): Collection;
    public function getAllClasses(): LengthAwarePaginator;
    public function getClassesByLevel(string $level): array;
}
