<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleServicePort;
use Nette\Schema\ValidationException;

class CreateMeetingScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository, protected ScheduleServicePort $service) {}

    public function execute(array $data): void
    {
        $student = $this->service->getStudentById($data['student_id']);
        
        if ($student->session <= 0) {
            throw new ValidationException('Insufficient sessions. Please purchase more sessions to book a meeting.');
        }

        $this->repository->createMeetingSchedule([
            'tutor_id' => $data['tutor_id'],
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'learning_method' => $data['learning_method'],
            'address' => $data['address']
        ]);

        $session = $student->session - 1;
        $this->service->updateStudent($data['student_id'], ['session' => $session]);

        $this->service->pushNotificationToUser($data['tutor_id'], [
            'title' => 'New Booking Request',
            'body' => 'You have a new booking request from ' . $student->name,
            'data' => [
                'student_name' => $student->name,
                'subject_id' => $data['subject_id'],
                'date' => $data['date'],
                'time' => $data['time'],
            ],
        ]);
    }
}