<?php

namespace App\Domains\UserProfile\Student\Entities;

class StudentEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        
        // User attributes
        public readonly ?string $id,
        public ?string $name,
        public readonly ?string $email,
        public readonly ?string $emailVerifiedAt,
        public readonly ?string $googleId,
        public readonly ?string $facebookId,
        public readonly ?string $role,
        public ?string $telephoneNumber,
        public ?string $telephoneVerifiedAt,
        public ?string $profilePhotoPath,
        public ?string $dateOfBirth,
        public ?string $gender,
        public ?string $religion,
        public ?array $homeAddress,
        public ?float $latitude,
        public ?float $longitude,

        // Student attributes
        public ?int $session,
        public ?int $classId,
    )
    {}
}
