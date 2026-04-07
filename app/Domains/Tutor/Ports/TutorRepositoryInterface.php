<?php

namespace App\Domains\Tutor\Ports;

interface TutorRepositoryInterface
{
    public function getByCriteria(array $filters);
}