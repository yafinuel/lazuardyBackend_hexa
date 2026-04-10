<?php

namespace App\Domains\Tutor\Ports;

use App\Domains\Tutor\Entities\TutorEntity;

interface TutorRepositoryInterface
{
    public function getTutorById(int $tutorId): TutorEntity;
    public function updateTutorBiodata(int $tutorId, array $data): void;
    public function getByCriteria(array $filters);
}