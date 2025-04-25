<?php

namespace App\Enums;

enum Currency: string {

    case GBP = '£';
    case USD = '$';
    case EUR = '€';

    public static function fromName(string $name): ?self
    {
        $name = strtoupper($name);

        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
    
}