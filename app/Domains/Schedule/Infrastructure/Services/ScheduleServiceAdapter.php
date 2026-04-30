<?php

namespace App\Domains\Schedule\Infrastructure\Services;

use App\Domains\Penalty\Actions\UserPenaltySetAction;
use App\Domains\Schedule\Actions\GetSchedulesThisMonthByTutorIdAction;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use App\Domains\Student\Actions\GetStudentByIdAction;
use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Domains\User\Actions\GetUserByIdAction;
use App\Domains\User\Entities\UserEntity;

class ScheduleServiceAdapter implements ScheduleServicePort
{
    public function __construct(
        protected UserPenaltySetAction $userPenaltySetAction,
        protected GetUserByIdAction $getUserByIdAction,
        protected GetStudentByIdAction $getStudentByIdAction,
        protected StudentRepositoryInterface $studentRepository,
        protected GetSchedulesThisMonthByTutorIdAction $getSchedulesThisMonthAction
    ) {}

    public function userPenaltySet(int $userId)
    {
        return $this->userPenaltySetAction->execute($userId);
    }

    public function getUserById(int $userId): UserEntity
    {
        return $this->getUserByIdAction->execute($userId);
    }

    public function getStudentById(int $studentId): StudentEntity
    {
        return $this->getStudentByIdAction->execute($studentId);
    }

    public function updateStudent(int $studentId, array $data): void
    {
        $this->studentRepository->update($studentId, $data);
    }

    public function getSchedulesThisMonthByTutorId(int $tutorId, int $paginate = 10)
    {
        return $this->getSchedulesThisMonthAction->execute($tutorId, $paginate);
    }
}