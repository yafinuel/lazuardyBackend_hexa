<?php

namespace App\Domains\OtpManager\Entities;

use App\Shared\Enums\OtpIdentifierEnum;
use App\Shared\Enums\OtpVerificationTypeEnum;

class OtpEntity {
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $identifier,
        public readonly ?OtpIdentifierEnum $identifierType,
        public readonly ?OtpVerificationTypeEnum $verificationType,
        public readonly ?string $code,
        public ?int $attempts=0,
        public ?bool $is_used,
        public ?string $expiredAt,
    ) {}
}
