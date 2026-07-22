<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'Open';
    case InProgress = 'In Progress';
    case Resolved = 'Resolved';
    case Closed = 'Closed';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
