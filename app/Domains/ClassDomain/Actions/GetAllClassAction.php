<?php

namespace App\Domains\ClassDomain\Actions;

use App\Domains\ClassDomain\Entities\ClassEntity;
use App\Domains\ClassDomain\Ports\ClassRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetAllClassAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ClassRepositoryInterface $repository) {}

    public function execute(): LengthAwarePaginator
    {
        $result = $this->repository->getAllClasses();

        return $result->through(function($class){
            return new ClassEntity(
                id: $class->id,
                name: $class->name,
                level: $class->level
            );
        });
    }
}
