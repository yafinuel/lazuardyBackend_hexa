<?php

namespace App\Shared\Enums;

enum OrderStatusEnum: string
{
    case UNPAID = 'unpaid';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    
    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

}
