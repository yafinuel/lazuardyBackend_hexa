<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetSchedulesThisMonthByTutorIdAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $tutorId, int $paginate = 10): array
    {
        $result = $this->repository->getSchedulesThisMonthByTutorId($tutorId, $paginate);
        $schedules = $result['schedules'];
        $studentCount = $result['studentCount'];
        
        $schedules = $schedules->through(function ($schedule) {
            return new ScheduleEntity(
                id: $schedule->id,
                tutorId: $schedule->tutor_id,
                studentId: $schedule->student_id,
                subjectId: $schedule->subject_id,
                date: $schedule->date,
                startTime: $schedule->start_time,
                endTime: $schedule->end_time,
                status: $schedule->status,
                learningMethod: $schedule->learning_method,
                address: $schedule->address,
                tutorName: $schedule->tutor ? $schedule->tutor->user->name : null,
                subjectName: $schedule->subject ? $schedule->subject->name : null,
                studentName: $schedule->student ? $schedule->student->user->name : null,
                tutorTelephoneNumber: $schedule->tutor ? $schedule->tutor->user->telephone_number : null,
                studentTelephoneNumber: $schedule->student ? $schedule->student->user->telephone_number : null
            );
        });

        $schedules = $schedules->toArray();
        $schedules['student_count'] = $studentCount;
        
        return $schedules;
    }
}