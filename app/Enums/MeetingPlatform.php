<?php

namespace App\Enums;

enum MeetingPlatform: string
{
    case Zoom  = 'zoom';
    case Meet  = 'meet';
    case Teams = 'teams';
    case Jitsi = 'jitsi';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Zoom  => 'Zoom',
            self::Meet  => 'Google Meet',
            self::Teams => 'Microsoft Teams',
            self::Jitsi => 'Jitsi',
            self::Other => 'Otro',
        };
    }
}
