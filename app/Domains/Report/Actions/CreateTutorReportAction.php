<?php

namespace App\Domains\Report\Actions;

use App\Domains\Report\Ports\ReportRepositoryInterface;
use App\Domains\Schedule\Ports\ScheduleRepositoryInterface;
use App\Shared\Enums\ScheduleStatusEnum;

class CreateTutorReportAction
{
    public function __construct(protected ReportRepositoryInterface $reportRepository, protected ScheduleRepositoryInterface $scheduleRepository) {}

    public function execute(int $scheduleId, int $tutorId, int $studentId, string $topic, string $notes): void
    {
        $this->reportRepository->createPresence($scheduleId, $tutorId, $studentId, $topic, $notes);
        $this->scheduleRepository->updateSchedule($scheduleId, ['status' => ScheduleStatusEnum::COMPLETED]);
    }
}