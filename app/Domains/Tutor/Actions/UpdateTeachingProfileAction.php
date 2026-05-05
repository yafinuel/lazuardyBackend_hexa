<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UpdateTeachingProfileAction
{
    public function __construct(protected TutorRepositoryInterface $tutorRepository, protected ScheduleRepositoryInterface $scheduleRepository) {}

    public function execute(int $tutorId, array $data): void
    {
        $tutorData = [
            'description' => $data['description'] ?? null,
            'learning_method' => $data['learning_method'] ?? null,
        ];

        DB::beginTransaction();
        try {
            $this->tutorRepository->update($tutorId, $tutorData);

            if (isset($data['schedules'])) {
                $validated = Validator::make(['schedules' => $data['schedules']], [
                    'schedules' => ['required', 'array', 'min:1'],
                    'schedules.*.day' => ['required', 'string'],
                    'schedules.*.time' => ['required', 'date_format:H:i'],
                ])->validate();

                $schedulesData = $validated['schedules'];
                $this->scheduleRepository->updateTutorSchedule($tutorId, $schedulesData);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update teaching profile: ' . $e->getMessage());
        }
    }
}