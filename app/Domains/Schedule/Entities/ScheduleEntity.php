<?php

namespace App\Domains\Schedule\Entities;

use Carbon\Carbon;

class ScheduleEntity {
    public function __construct(
        public int $id,
        public int $tutorId,
        public int $studentId,
        public int $subjectId,
        public Carbon $date,
        public Carbon $startTime,
        public Carbon $endTime,
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
