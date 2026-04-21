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
        public ?string $meetingLink,
        public ?string $address,
        public ?string $tutorName,
        public ?string $subjectName,
        public ?string $studentName,
        public ?string $tutorTelephoneNumber,
        public ?string $studentTelephoneNumber
    ) {}
}
