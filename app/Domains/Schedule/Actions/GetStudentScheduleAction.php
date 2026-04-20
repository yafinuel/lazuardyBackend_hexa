<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Carbon\Carbon;

class GetStudentScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $studentId, Carbon $date, int $paginate = 10)
    {
        $result = $this->repository->getStudentSchedule($studentId, $date, $paginate);
        
        return $result->through(function ($schedule) {
            $startTime = Carbon::createFromFormat('H:i:s', $schedule->time);
            $endTime = (clone $startTime)->addHour();
            return new ScheduleEntity(
                id: $schedule->id,
                tutorId: $schedule->tutor_id,
                studentId: $schedule->student_id,
                subjectId: $schedule->subject_id,
                date: $schedule->date,
                startTime: $startTime->format('H:i:s'),
                endTime: $endTime->format('H:i:s'),
                status: $schedule->status->value,
                learningMethod: $schedule->learning_method,
                meetingLink: $schedule->meeting_link,
                tutorName: $schedule->tutor?->user?->name,
                subjectName: $schedule->subject?->name,
                studentName: $schedule->student?->user?->name,
                tutorTelephoneNumber: $schedule->tutor?->user?->telephone_number,
                studentTelephoneNumber: $schedule->student?->user?->telephone_number
            );
        });
    }
}