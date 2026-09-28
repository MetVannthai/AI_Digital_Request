<?php

namespace App\Support;

class TimeBasedGreeting
{
    public static function current(): string
    {
        return self::forHour(now()->hour);
    }

    public static function forHour(int $hour): string
    {
        return match (true) {
            $hour >= 5 && $hour < 12 => 'Good morning',
            $hour >= 12 && $hour < 17 => 'Good afternoon',
            $hour >= 17 && $hour < 21 => 'Good evening',
            default => 'Good night',
        };
    }
}
