<?php

namespace App\Domains\UserProfile\Tutor\Ports;

use App\Domains\UserProfile\Tutor\Entities\TutorEntity;

interface TutorRepositoryInterface
{
    public function getTutorBiodata(int $tutorId): TutorEntity;
    public function updateTutorBiodata(int $tutorId, array $data): void;
}
