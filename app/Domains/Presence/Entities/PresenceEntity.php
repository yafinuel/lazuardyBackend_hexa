<?php

namespace App\Domains\Presence\Entities;

class PresenceEntity {
    public function __construct(
        public readonly int $id,
        public readonly int $scheduleId,
        public readonly int $tutorId,
        public readonly string $tutorName,
        public readonly int $studentId,
        public readonly string $studentName,
        public readonly string $topic,
        public readonly string $notes,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
