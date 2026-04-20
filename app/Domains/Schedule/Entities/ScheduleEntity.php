<?php

namespace App\Domains\Schedule\Entities;

class ScheduleEntity {
    public function __construct(
        public int $id,
        public int $tutorId,
        public int $studentId,
        public int $subjectId,
        public string $date,
        public string $startTime,
        public string $endTime,
        public string $status,
        public string $learningMethod,
        public ?string $meetingLink = null,
        public ?string $tutorName = null,
        public ?string $subjectName = null,
        public ?string $studentName = null,
        public ?string $tutorTelephoneNumber = null,
        public ?string $studentTelephoneNumber = null
    ) {}
}
