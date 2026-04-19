<?php

namespace App\Shared\Enums;

enum FileStatusEnum: string
{
    case TEMPORARY_STORAGE = "temporary_storage";
    case FIXED_STORAGE = "fixed_storage";

    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
