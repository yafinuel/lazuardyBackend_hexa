<?php

namespace App\Domains\UserProfile\Tutor\Entities;

class TutorEntity
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
        public ?array $address,
        public ?string $latitude,
        public ?string $longitude,

        // Tutor attributes
        public ?string $education,
        public ?int $salary,
        public ?int $price,
        public ?string $description,
        public ?string $bankCode,
        public ?string $accountNumber,
        public ?array $learningMethod,
        public ?string $sanction,
        public ?string $status,
    ) {}
}
