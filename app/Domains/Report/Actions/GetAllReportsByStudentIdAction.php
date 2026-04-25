<?php

namespace App\Domains\Report\Actions;

use App\Domains\Report\Entities\ReportEntity;
use App\Domains\Report\Ports\ReportRepositoryInterface;

class GetAllReportsByStudentIdAction
{
    public function __construct(protected ReportRepositoryInterface $repository) {}

    public function execute(int $studentId, array $data)
    {
        $result = $this->repository->getAllReportsByStudentId($studentId, $data['paginate'] ?? 10);

        return $result->through(function ($report) {
            return new ReportEntity(
                id: $report->id,
                scheduleId: $report->schedule_id,
                tutorId: $report->tutor_id,
                tutorName: $report->tutor->user->name,
                studentId: $report->student_id,
                studentName: $report->student->user->name,
                topic: $report->topic,
                notes: $report->notes,
                createdAt: $report->created_at,
                updatedAt: $report->updated_at,
            );
        });
    }
}