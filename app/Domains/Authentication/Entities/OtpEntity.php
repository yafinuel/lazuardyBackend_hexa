<?php

namespace App\Domains\Authentication\Entities;

class OtpEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $identifier,
        public readonly ?string $identifierType,
        public readonly ?string $verificationType,
        public readonly ?string $code,
        public ?int $attempts=0,
        public ?bool $is_used,
        public ?string $expiredAt,
    )
    {}
}
