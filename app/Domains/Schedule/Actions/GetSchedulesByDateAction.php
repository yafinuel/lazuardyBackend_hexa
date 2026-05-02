<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Carbon\Carbon;

class GetSchedulesByDateAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $userId, Carbon $date, int $paginate = 10)
    {
        $result = $this->repository->getSchedulesByDate($userId, $date, $paginate);
        
        return $result->through(function ($schedule) {
            return new ScheduleEntity(
                id: $schedule->id,
                tutorId: $schedule->tutor_id,
                studentId: $schedule->student_id,
                subjectId: $schedule->subject_id,
                date: $schedule->date,
                startTime: Carbon::createFromFormat('H:i:s', $schedule->time),
                endTime: Carbon::createFromFormat('H:i:s', $schedule->time)->addHour(),
                status: $schedule->status,
                learningMethod: $schedule->learning_method,
                address: $schedule->address,
                tutorName: $schedule->tutor?->user?->name,
                subjectName: $schedule->subject?->name,
                studentName: $schedule->student?->user?->name,
                tutorTelephoneNumber: $schedule->tutor?->user?->telephone_number,
                studentTelephoneNumber: $schedule->student?->user?->telephone_number
            );
        });
    }
}