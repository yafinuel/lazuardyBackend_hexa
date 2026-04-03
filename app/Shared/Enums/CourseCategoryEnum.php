<?php

namespace App\Shared\Enums;

enum CourseCategoryEnum: string
{
    case ACADEMIC = 'akademik';
    case GENERAL = 'umum';
    
    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
