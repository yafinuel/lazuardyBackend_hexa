<?php

namespace App\Domains\Penalty\Infrastructure\Services;

use App\Domains\Penalty\Ports\PenaltyServicePort;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Entities\TutorEntity;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class PenaltyServiceAdapter implements PenaltyServicePort
{
    public function __construct(
        protected GetStudentByIdAction $getStudentByIdAction,
        protected GetTutorByIdAction $getTutorByIdAction,
        protected GetUserByIdAction $getUserByIdAction
    ) {}

    public function getStudentById(string $studentId): StudentEntity
    {
        return $this->getStudentByIdAction->execute($studentId);
    }

    public function getTutorById(string $tutorId): TutorEntity
    {
        return $this->getTutorByIdAction->execute($tutorId);
    }

    public function getUserById(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }
}