<?php

namespace App\Enums;

enum TicketCategory: string
{
    case Hardware = 'Hardware';
    case Software = 'Software';
    case Network = 'Network';
    case Access = 'Access';
    case Other = 'Other';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
