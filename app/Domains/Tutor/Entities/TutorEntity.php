<?php

namespace App\Domains\Tutor\Entities;

use App\Shared\Enums\TutorStatusEnum;
use Carbon\Carbon;

class TutorEntity{

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

        // Tutor attributes
        public ?int $salary,
        public ?string $description,
        public ?array $education,
        public ?string $bankCode,
        public ?string $accountHolderName,
        public ?string $accountNumber,
        public ?array $learningMethod,
        public ?TutorStatusEnum $status,
        public ?float $avgRate,
        public ?Carbon $createdAt,
        
        public ?array $subjects = null,
        ) {}
        }
