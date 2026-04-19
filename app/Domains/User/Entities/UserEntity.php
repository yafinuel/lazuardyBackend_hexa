<?php

namespace App\Domains\User\Entities;

use App\Shared\Enums\GenderEnum;
use Carbon\Carbon;

class UserEntity {
    public function __construct(
        public readonly ?string $id,
        public ?string $name,
        public readonly ?string $email,
        public readonly ?string $emailVerifiedAt,
        public ?string $password,
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
    ) {}
}
