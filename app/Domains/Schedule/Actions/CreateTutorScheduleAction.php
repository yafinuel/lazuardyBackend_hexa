<?php

namespace App\Domains\Schedule\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateTutorScheduleAction
{
    public function __construct(protected ScheduleRepositoryInterface $repository) {}

    public function execute(int $tutorId, array $schedules): bool
    {
        $validator = Validator::make(['schedules' => $schedules], [
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.day' => ['required', 'string'],
            'schedules.*.time' => ['required', 'date_format:H:i'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->createTutorSchedule($tutorId, $schedules);
    }
}