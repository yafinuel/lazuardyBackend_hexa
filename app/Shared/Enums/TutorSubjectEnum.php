<?php

namespace App\Shared\Enums;

enum TutorSubjectEnum: string
{
    case PENDING = "pending";
    case VERIFIED = "verified";
    case REJECTED = "rejected";

    public function displayName() : string 
    {
        return match($this) 
        {
            self::PENDING => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi',
            self::REJECTED => 'Ditolak', 
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
