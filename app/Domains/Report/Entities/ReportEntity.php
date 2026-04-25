<?php

namespace App\Domains\Report\Entities;

class ReportEntity {
    public function __construct(
        public readonly int $id,
        public readonly int $scheduleId,
        public readonly int $tutorId,
        public readonly int $tutorName,
        public readonly int $studentId,
        public readonly int $studentName,
        public readonly string $topic,
        public readonly string $notes,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
