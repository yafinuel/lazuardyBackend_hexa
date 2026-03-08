<?php

namespace App\Domains\Authentication\Entities;

class UserAuthEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $email,
        public readonly ?string $emailVerifiedAt,
        public readonly ?string $password,
        public ?string $google_id,
        public ?string $facebook_id,
    )
    {}
}
