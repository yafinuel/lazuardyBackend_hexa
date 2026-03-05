<?php

namespace App\Shared\Enums;

enum ReligionEnum: string
{
    case ISLAM = 'islam';
    case KRISTEN = 'kristen';
    case KATOLIK = 'katolik';
    case HINDU = 'hindu';
    case BUDDHA = 'buddha';
    case KONGHUCU = 'konghucu';

    
    public function displayName() : string 
    {
        return match($this) 
        {
            self::ISLAM => 'Islam',
            self::KRISTEN => 'Kristen',
            self::KATOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
            self::KONGHUCU => 'Konghucu',
        };
    }
    
    public static function tryFromDisplayName(string $displayName): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->displayName() === $displayName) {
                return $case;
            }
        }
        return null;
    }

    public static function list(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
    
    public static function displayList(): array
    {
        return array_map(fn($case) => $case->displayName($case->value), self::cases());
    }
}
