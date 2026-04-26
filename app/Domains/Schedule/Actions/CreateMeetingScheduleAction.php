<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleServicePort;

class CreateMeetingScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServicePort $service) {}

    public function execute(array $data): void
    {
        $this->repository->createMeetingSchedule([
            'tutor_id' => $data['tutor_id'],
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'learning_method' => $data['learning_method'],
            'address' => $data['address']
        ]);

        $student = $this->service->getStudentById($data['student_id']);
        $session = $student->session - 1;
        $this->service->updateStudent($data['student_id'], ['session' => $session]);
    }
}