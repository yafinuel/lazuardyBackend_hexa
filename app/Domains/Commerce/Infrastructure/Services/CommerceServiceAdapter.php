<?php

namespace App\Domains\Commerce\Infrastructure\Services;

use App\Domains\Commerce\Ports\CommerceServicePort;
use App\Domains\Package\Actions\GetPackageByIdAction;
use App\Domains\Schedule\Actions\GetSchedulesThisMonthByTutorIdAction;
use App\Domains\Student\Actions\UpdateStudentSessionAction;
use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Domains\Tutor\Actions\UpdateTutorByIdAction;
use App\Domains\Tutor\Entities\TutorEntity;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class CommerceServiceAdapter implements CommerceServicePort
{
    public function __construct(
        protected GetPackageByIdAction $getPackageByIdAction,
        protected GetUserByIdAction $getUserByIdAction,
        protected GetSchedulesThisMonthByTutorIdAction $getSchedulesThisMonthByTutorIdAction,
        protected UpdateStudentSessionAction $updateStudentSessionAction,
        protected GetTutorByIdAction $getTutorByIdAction,
        protected UpdateTutorByIdAction $updateTutorByIdAction
    ) {}

    public function getPackageByIdAction(int $packageId)
    {
        return $this->getPackageByIdAction->execute($packageId);
    }

    public function getUserByIdAction(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }

    public function getSchedulesThisMonthByTutorId(int $tutorId): LengthAwarePaginator
    {
        return $this->getSchedulesThisMonthByTutorIdAction->execute($tutorId);
    }

    public function updateStudentSession(int $studentId, int $sessionToAdd = 1)
    {
        return $this->updateStudentSessionAction->execute($studentId, $sessionToAdd);
    }

    public function getTutorById(int $tutorId): TutorEntity
    {
        return $this->getTutorByIdAction->execute($tutorId);
    }

    public function updateTutorById(int $tutorId, array $data)
    {
        return $this->updateTutorByIdAction->execute($tutorId, $data);
    }
}