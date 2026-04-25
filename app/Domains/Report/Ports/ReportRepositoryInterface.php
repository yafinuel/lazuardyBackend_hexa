<?php

namespace App\Domains\Report\Ports;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface
{
    public function getAllReportsByStudentId(int $studentId, int $perPage = 10): LengthAwarePaginator;
}