<?php

namespace App\Domains\Penalty\Infrastructure\Services;

use App\Domains\Penalty\Ports\PenaltyServicePort;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Entities\TutorEntity;

class PenaltyServiceAdapter implements PenaltyServicePort
{
    public function __construct(
        protected GetStudentByIdAction $getStudentByIdAction,
        protected GetTutorByIdAction $getTutorByIdAction,
    ) {}

    public function getStudentById(string $studentId): StudentEntity
    {
        return $this->getStudentByIdAction->execute($studentId);
    }

    public function getTutorById(string $tutorId): TutorEntity
    {
        return $this->getTutorByIdAction->execute($tutorId);
    }
}