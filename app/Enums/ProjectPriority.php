<?php

namespace App\Enums;

enum ProjectPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case): string => $case->value, self::cases());
    }
}
