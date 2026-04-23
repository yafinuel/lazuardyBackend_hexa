<?php

namespace App\Domains\Student\Entities;

use Carbon\Carbon;

class StudentEntity {
    public function __construct(
        // User attributes
        public readonly ?string $id,
        public ?string $name,
        public readonly ?string $email,
        public readonly ?string $emailVerifiedAt,
        public ?string $telephoneNumber,
        public ?string $telephoneVerifiedAt,
        public ?string $profilePhotoUrl,
        public ?string $dateOfBirth,
        public ?string $gender,
        public ?string $religion,
        public ?array $homeAddress,
        public ?string $role,
        public ?int $warning,
        public ?Carbon $sanction,
        public ?string $latitude,
        public ?string $longitude,
        
        // Student attributes
        public ?string $session,
        public ?int $classId,
        public ?string $className,
    ) {}
}
