<?php

namespace App\Domains\Parent\Infrastructure\Repository;

use App\Domains\Parent\Entities\ParentEntity;
use App\Domains\Parent\Ports\ParentRepositoryInterface;
use App\Models\ParentModel;

class EloquentParentRepository implements ParentRepositoryInterface
{
    public function getParentById(int $parentId): ParentEntity
    {
        $parent = ParentModel::with('user')->where('user_id', $parentId)->firstOrFail();

        return new ParentEntity(
            id: $parent->user_id,
            email: $parent->user->email,
            studentId: $parent->student_id,
        );
    }
}