<?php

namespace App\Domains\Penalty\Ports;

use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Tutor\Entities\TutorEntity;

interface PenaltyServicePort
{
    public function getStudentById(string $studentId): StudentEntity;
    public function getTutorById(string $tutorId): TutorEntity;
}