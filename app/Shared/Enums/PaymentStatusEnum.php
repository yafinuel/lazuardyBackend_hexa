<?php

namespace App\Shared\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
    case FAILED = 'failed';

    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
