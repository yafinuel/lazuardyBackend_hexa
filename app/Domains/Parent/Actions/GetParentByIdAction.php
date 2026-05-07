<?php

namespace App\Domains\Parent\Actions;

use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\Parent\Ports\ParentRepositoryInterface;

class GetParentByIdAction
{
    public function __construct(protected ParentRepositoryInterface $repository) {}

    public function execute(int $parentId): ParentEntity
    {
        return $this->repository->getParentById($parentId);
    }
}