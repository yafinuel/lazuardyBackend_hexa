<?php

namespace App\Shared\Enums;

enum FileTypeEnum: string
{
    case ID_CARD = 'id_card';
    case CV = 'cv';
    case CERTIFICATE = 'cerfiticate';

    public function displayName() : string 
    {
        return match($this) 
        {
            self::ID_CARD => 'ID CARD',
            self::CV => 'CV',
            self::CERTIFICATE => 'Cerfiticate',
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
    public static function list() : array 
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function displayList() : array 
    {
        return array_map(fn($case) => $case->displayName(), self::cases());
    }
}
