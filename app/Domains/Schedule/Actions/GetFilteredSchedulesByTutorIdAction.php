<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Entities\ScheduleEntity;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetFilteredSchedulesByTutorIdAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $tutorId, array $filters, int $paginate = 10): LengthAwarePaginator
    {
        $result = $this->repository->getFilteredSchedulesByTutorId($tutorId, $filters, $paginate);

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