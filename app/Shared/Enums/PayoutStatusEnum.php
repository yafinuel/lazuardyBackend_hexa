<?php

namespace App\Shared\Enums;

enum PayoutStatusEnum: string
{
    case REQUESTED = 'REQUESTED';
    case PENDING = 'PENDING';
    case SUCCESS = 'SUCCESS';
    case REJECTED = 'REJECTED';
    case FAILED = 'FAILED';
    
    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
