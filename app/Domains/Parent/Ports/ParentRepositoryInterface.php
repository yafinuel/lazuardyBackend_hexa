<?php

namespace App\Domains\Parent\Ports;

interface ParentRepositoryInterface
{
    public function getParentById(int $parentId);
}